<?php

declare(strict_types=1);

namespace Chorale\Console;

use Chorale\Config\ConfigLoaderInterface;
use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Plan\PlanBuilderInterface;
use Chorale\Plan\PlanStepInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A true dry-run: prints only actionable steps by default.
 * Use --show-all to also show no-op summaries for debugging.
 */
final class PlanCommand extends Command
{
    protected static $defaultName = 'chorale plan';

    protected static $defaultDescription = 'Build and print a dry-run plan of actionable steps.';

    public function __construct(
        private readonly ConsoleStyleFactory $styleFactory,
        private readonly ConfigLoaderInterface $configLoader,
        private readonly PlanBuilderInterface $planner,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('plan')
            ->setDescription('Build and print a dry-run plan of actionable steps.')
            ->setHelp(<<<'HELP'
Generates a dry-run plan of actionable steps without modifying files.

What it checks
- Package versions: aligns each package's version with the monorepo root version.
- Package metadata: mirrors selected composer.json sections using the rule engine.
- Root composer: updates require/replace for all packages and merges dependencies.
- Split: decides which packages need a split and why (content, remote, policy).

Positional arguments
- package: Optional composer package name (e.g. vendor/name) to focus the plan
  on a single package. Useful to inspect exact diffs in large monorepos.

Common options
- Verbosity controls detail:
  - default: concise one-line summaries
  - -v: detailed blocks
  - -vv: detailed + include no-op summaries
  - -vvv: everything above plus full JSON plan at end
- --show-all: Include no-op summaries (same as -vv or higher).
- --json: Output as JSON for apply or tooling (includes delta metadata).
- --project-root=PATH: Project root (defaults to current directory).
- --paths=DIR ...: Limit discovery to specific package paths (directories).
- --force-split: Plan split steps even when content appears unchanged.
- --verify-remote: Verify remote state when local lockfiles are missing/stale.
- --strict: Exit non-zero if issues are detected (e.g., missing root version,
  unresolved conflicts).

Examples
  chorale plan
  chorale plan -v
  chorale plan -vv
  chorale plan --json > plan.json
  chorale plan sonsofphp/cache
  chorale plan sonsofphp/cache -v
  chorale plan --paths src/SonsOfPHP/Component/Cache

Notes
- Delta notation [+added/-removed/~changed] summarizes composer map changes.
- JSON output includes meta.delta_* fields for machine processing.
- Default behavior is fully opt-in: no composer fields are mirrored or merged
  unless you configure rules in chorale.yaml or set package-level overrides.
HELP)
            ->addArgument('package', InputArgument::OPTIONAL, 'Composer package name to focus on (e.g. vendor/name).')
            ->addOption('project-root', null, InputOption::VALUE_REQUIRED, 'Project root (default: CWD).')
            ->addOption('paths', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Limit to specific package paths', [])
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON instead of human-readable.')
            // --concise retained for compatibility; default output is concise
            ->addOption('concise', null, InputOption::VALUE_NONE, 'Force one-line summaries only; omit detailed blocks.')
            ->addOption('show-all', null, InputOption::VALUE_NONE, 'Show no-op summaries (does not turn them into steps).')
            ->addOption('force-split', null, InputOption::VALUE_NONE, 'Force split steps even if unchanged.')
            ->addOption('verify-remote', null, InputOption::VALUE_NONE, 'Verify remote state if lockfile is missing/stale.')
            ->addOption('strict', null, InputOption::VALUE_NONE, 'Fail on missing root version / unresolved conflicts / remote failures.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = $this->styleFactory->create($input, $output);
        $focusPackage = (string) ($input->getArgument('package') ?? '');
        $root   = rtrim((string) ($input->getOption('project-root') ?: getcwd()), '/');
        /** @var list<string> $paths */
        $paths  = (array) $input->getOption('paths');
        $json   = (bool) $input->getOption('json');
        $verbosity = $output->getVerbosity();
        $explicitConcise = (bool) $input->getOption('concise');
        // Concise by default; -v or higher switches to detailed unless --concise is given
        $concise = $explicitConcise || ($verbosity <= OutputInterface::VERBOSITY_NORMAL);
        // Show no-op summaries at -vv or when explicitly requested
        $showAll = (bool) $input->getOption('show-all') || ($verbosity >= OutputInterface::VERBOSITY_VERY_VERBOSE);
        $force  = (bool) $input->getOption('force-split');
        $verify = (bool) $input->getOption('verify-remote');
        $strict = (bool) $input->getOption('strict');

        $config = $this->configLoader->load($root);
        if ($config === []) {
            $io->warning('No chorale.yaml found. Run "chorale setup" first.');
            return 2;
        }

        $result = $this->planner->build($root, $config, [
            'paths' => $paths,
            'package' => $focusPackage,
            'show_all' => $showAll,
            'force_split' => $force,
            'verify_remote' => $verify,
            'strict' => $strict,
        ]);

        // Planner returns an associative array with 'steps' and optional 'noop'
        $steps = $result['steps'] ?? [];
        $noop  = $result['noop']  ?? [];

        if ($json) {
            $payload = [
                'version' => 1,
                'steps'   => array_map(static fn(PlanStepInterface $s): array => $s->toArray(), $steps),
                'noop'    => $showAll ? $noop : [],
            ];
            $encoded = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            if ($encoded === false) {
                $io->error('Failed to encode plan.');
                return 2;
            }

            $output->writeln($encoded);
            // Non-zero exit in strict mode when planner flags issues
            return (int) ($result['exit_code'] ?? 0);
        }

        $this->renderHuman($io, $steps, $showAll ? $noop : [], $concise);

        // At -vvv print the full JSON payload after human output
        if ($verbosity >= OutputInterface::VERBOSITY_DEBUG) {
            $io->newLine();
            $io->section('Full JSON plan');
            $payload = [
                'version' => 1,
                'steps'   => array_map(static fn(PlanStepInterface $s): array => $s->toArray(), $steps),
                'noop'    => $showAll ? $noop : [],
            ];
            $encoded = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            if ($encoded !== false) {
                $output->writeln($encoded);
            }
        }

        return (int) ($result['exit_code'] ?? 0);
    }

    /** @param list<PlanStepInterface> $steps */
    private function renderHuman(SymfonyStyle $io, array $steps, array $noop, bool $concise): void
    {
        $io->title('Chorale Plan');

        $byType = [];
        foreach ($steps as $s) {
            $byType[$s->type()][] = $s;
        }

        $sections = [
            'split' => 'Split steps',
            'package-version-update' => 'Package versions',
            'package-metadata-sync'  => 'Package metadata',
            'composer-root-update'   => 'Root composer: aggregator',
            'composer-root-merge'    => 'Root composer: dependency merge',
            'composer-root-rebuild'  => 'Root composer: maintenance',
        ];

        $any = false;
        foreach ($sections as $type => $label) {
            if (empty($byType[$type])) {
                continue;
            }

            $any = true;
            $io->section($label);
            foreach ($byType[$type] as $s) {
                $a = $s->toArray();
                $io->writeln('  • ' . $this->humanLine($type, $a));

                // Detailed view for certain step types
                if ($concise) {
                    continue;
                }

                if ($type === 'package-metadata-sync') {
                    $apply = (array) ($a['apply'] ?? []);
                    if ($apply !== []) {
                        $io->writeln('      apply:');
                        $this->writelnPrettyArray($io, $apply, 8);
                    }

                    if (!empty($a['overrides_used'])) {
                        $io->writeln('      overrides_used: ' . implode(',', (array) $a['overrides_used']['values']));
                    }
                } elseif ($type === 'composer-root-update') {
                    $req = (array) ($a['require'] ?? []);
                    $rep = (array) ($a['replace'] ?? []);
                    if ($req !== []) {
                        $io->writeln('      require:');
                        $this->writelnKeyVals($io, $req, 8);
                        if (!empty($a['meta']['delta_require'])) {
                            $io->writeln('        delta: ' . $this->formatDelta((array) $a['meta']['delta_require']));
                        }
                    }

                    if ($rep !== []) {
                        $io->writeln('      replace:');
                        $this->writelnKeyVals($io, $rep, 8);
                        if (!empty($a['meta']['delta_replace'])) {
                            $io->writeln('        delta: ' . $this->formatDelta((array) $a['meta']['delta_replace']));
                        }
                    }
                } elseif ($type === 'composer-root-merge') {
                    foreach (['require','require-dev'] as $k) {
                        $vals = (array) ($a[$k] ?? []);
                        if ($vals !== []) {
                            $io->writeln(sprintf('      %s:', $k));
                            $this->writelnKeyVals($io, $vals, 8);
                            $metaKey = 'delta_' . str_replace('-', '_', $k);
                            if (!empty($a['meta'][$metaKey])) {
                                $io->writeln('        delta: ' . $this->formatDelta((array) $a['meta'][$metaKey]));
                            }
                        }
                    }

                    if (!empty($a['conflicts'])) {
                        $io->writeln('      conflicts:');
                        $this->writelnPrettyArray($io, (array) $a['conflicts'], 8);
                    }
                }
            }
        }

        if (!$any) {
            $io->writeln('No steps. Nothing to do.');
        }

        if ($noop !== []) {
            $io->newLine();
            $io->section('No-op summary (debug)');
            foreach ($noop as $group => $rows) {
                $io->writeln(sprintf('  - %s: ', $group) . count($rows));
            }
        }

        $io->comment('Use "--json" to export this plan for apply.');
    }

    /** @param array<string,mixed> $a */
    private function humanLine(string $type, array $a): string
    {
        return match ($type) {
            'split' => sprintf(
                '%s → %s [%s] (branch %s, tag %s)%s',
                $a['path'] ?? '',
                $a['repo'] ?? '',
                $a['splitter'] ?? '',
                $a['branch'] ?? 'n/a',
                $a['tag_strategy'] ?? 'n/a',
                empty($a['reasons']) ? '' : ' {' . implode(',', (array) $a['reasons']) . '}'
            ),
            'package-version-update' => sprintf(
                '%s — set version %s%s%s',
                $a['name'] ?? $a['path'] ?? '',
                $a['version'] ?? '',
                isset($a['current_version']) ? ' (was ' . ($a['current_version'] ?? 'n/a') . ')' : '',
                isset($a['reason']) ? ' [' . $a['reason'] . ']' : ''
            ),
            'package-metadata-sync'  => sprintf(
                '%s — %s%s',
                $a['name'] ?? $a['path'] ?? '',
                'mirror ' . implode(',', array_keys((array) ($a['apply'] ?? []))),
                empty($a['overrides_used']) ? '' : ' [overrides: ' . implode(',', (array) $a['overrides_used']['values']) . ']'
            ),
            'composer-root-update'   => sprintf(
                'update %s (version %s, require %d%s, replace %d%s)',
                $a['root'] ?? '',
                $a['root_version'] ?? 'n/a',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['meta']['delta_require']) ? ' [' . $this->formatDelta((array) $a['meta']['delta_require']) . ']' : '',
                isset($a['replace']) ? count((array) $a['replace']) : 0,
                isset($a['meta']['delta_replace']) ? ' [' . $this->formatDelta((array) $a['meta']['delta_replace']) . ']' : ''
            ),
            'composer-root-merge'    => sprintf(
                'require %d%s, require-dev %d%s%s',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['meta']['delta_require']) ? ' [' . $this->formatDelta((array) $a['meta']['delta_require']) . ']' : '',
                isset($a['require-dev']) ? count((array) $a['require-dev']) : 0,
                isset($a['meta']['delta_require_dev']) ? ' [' . $this->formatDelta((array) $a['meta']['delta_require_dev']) . ']' : '',
                empty($a['conflicts']) ? '' : ' [conflicts: ' . count((array) $a['conflicts']) . ']'
            ),
            'composer-root-rebuild'  => sprintf('actions: %s', implode(',', (array) ($a['actions'] ?? []))),
            default => json_encode($a, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: $type,
        };
    }

    /** Pretty-print an array as JSON with indentation. */
    private function writelnPrettyArray(SymfonyStyle $io, array $data, int $indent = 0): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return;
        }

        $pad = str_repeat(' ', $indent);
        foreach (explode("\n", $json) as $line) {
            $io->writeln($pad . $line);
        }
    }

    /** Print key => value pairs (flat map) with indentation. */
    private function writelnKeyVals(SymfonyStyle $io, array $map, int $indent = 0): void
    {
        $pad = str_repeat(' ', $indent);
        foreach ($map as $k => $v) {
            $io->writeln(sprintf('%s- %s: %s', $pad, (string) $k, is_scalar($v) ? (string) $v : json_encode($v, JSON_UNESCAPED_SLASHES)));
        }
    }

    private function formatDelta(array $delta): string
    {
        $added = (int) ($delta['added'] ?? 0);
        $removed = (int) ($delta['removed'] ?? 0);
        $changed = (int) ($delta['changed'] ?? 0);
        return sprintf('+%d/-%d/~%d', $added, $removed, $changed);
    }
}
