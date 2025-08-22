<?php

declare(strict_types=1);

namespace Chorale\Console;

use Chorale\Config\ConfigLoaderInterface;
use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Plan\PlanBuilderInterface;
use Chorale\Plan\PlanStepInterface;
use Symfony\Component\Console\Command\Command;
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
            ->setHelp('Generates a plan of changes without modifying files. Use --json to export for apply.')
            ->addOption('project-root', null, InputOption::VALUE_REQUIRED, 'Project root (default: CWD).')
            ->addOption('paths', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Limit to specific package paths', [])
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON instead of human-readable.')
            ->addOption('show-all', null, InputOption::VALUE_NONE, 'Show no-op summaries (does not turn them into steps).')
            ->addOption('force-split', null, InputOption::VALUE_NONE, 'Force split steps even if unchanged.')
            ->addOption('verify-remote', null, InputOption::VALUE_NONE, 'Verify remote state if lockfile is missing/stale.')
            ->addOption('strict', null, InputOption::VALUE_NONE, 'Fail on missing root version / unresolved conflicts / remote failures.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = $this->styleFactory->create($input, $output);
        $root   = rtrim((string) ($input->getOption('project-root') ?: getcwd()), '/');
        /** @var list<string> $paths */
        $paths  = (array) $input->getOption('paths');
        $json   = (bool) $input->getOption('json');
        $showAll = (bool) $input->getOption('show-all');
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

        $this->renderHuman($io, $steps, $showAll ? $noop : []);
        return (int) ($result['exit_code'] ?? 0);
    }

    /** @param list<PlanStepInterface> $steps */
    private function renderHuman(SymfonyStyle $io, array $steps, array $noop): void
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
                '%s → %s [%s]%s',
                $a['path'] ?? '',
                $a['repo'] ?? '',
                $a['splitter'] ?? '',
                empty($a['reasons']) ? '' : ' {' . implode(',', (array) $a['reasons']) . '}'
            ),
            'package-version-update' => sprintf('%s — set version %s', $a['name'] ?? $a['path'] ?? '', $a['version'] ?? ''),
            'package-metadata-sync'  => sprintf(
                '%s — %s%s',
                $a['name'] ?? $a['path'] ?? '',
                'mirror ' . implode(',', array_keys((array) ($a['apply'] ?? []))),
                empty($a['overrides_used']) ? '' : ' [overrides: ' . implode(',', (array) $a['overrides_used']['values']) . ']'
            ),
            'composer-root-update'   => sprintf(
                'update %s (version %s, require %d, replace %d)',
                $a['root'] ?? '',
                $a['root_version'] ?? 'n/a',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['replace']) ? count((array) $a['replace']) : 0
            ),
            'composer-root-merge'    => sprintf(
                'require %d, require-dev %d%s',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['require-dev']) ? count((array) $a['require-dev']) : 0,
                empty($a['conflicts']) ? '' : ' [conflicts: ' . count((array) $a['conflicts']) . ']'
            ),
            'composer-root-rebuild'  => sprintf('actions: %s', implode(',', (array) ($a['actions'] ?? []))),
            default => json_encode($a, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: $type,
        };
    }
}
