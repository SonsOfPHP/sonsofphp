<?php

declare(strict_types=1);

namespace Chorale\Console;

use Chorale\Config\ConfigDefaultsInterface;
use Chorale\Config\ConfigLoaderInterface;
use Chorale\Config\ConfigNormalizerInterface;
use Chorale\Config\ConfigWriterInterface;
use Chorale\Config\SchemaValidatorInterface;
use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Discovery\ComposerMetadataInterface;
use Chorale\Discovery\PackageIdentityInterface;
use Chorale\Discovery\PackageScannerInterface;
use Chorale\Discovery\PatternMatcherInterface;
use Chorale\IO\JsonReporterInterface;
use Chorale\Repo\RepoResolverInterface;
use Chorale\Rules\RequiredFilesCheckerInterface;
use Chorale\Telemetry\RunSummaryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

//#[AsCommand(name: 'setup')]
final class SetupCommand extends Command
{
    protected static $defaultName = 'setup';

    protected static $defaultDescription = 'Create or update chorale.yaml by scanning src/ and applying defaults.';

    public function __construct(
        private readonly ConsoleStyleFactory $styleFactory,
        private readonly ConfigLoaderInterface $configLoader,
        private readonly ConfigWriterInterface $configWriter,
        private readonly ConfigNormalizerInterface $configNormalizer,
        private readonly SchemaValidatorInterface $schemaValidator,
        private readonly ConfigDefaultsInterface $defaults,
        private readonly PackageScannerInterface $scanner,
        private readonly PatternMatcherInterface $matcher,
        private readonly RepoResolverInterface $resolver,
        private readonly PackageIdentityInterface $identity,
        private readonly RequiredFilesCheckerInterface $requiredFiles,
        private readonly JsonReporterInterface $jsonReporter,
        private readonly RunSummaryInterface $summary,
        private readonly ComposerMetadataInterface $composerMeta,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('setup')
            ->setDescription('Create or update chorale.yaml by scanning src/ and applying defaults.')
            ->addOption('non-interactive', null, InputOption::VALUE_NONE, 'Never prompt.')
            ->addOption('accept-all', null, InputOption::VALUE_NONE, 'Accept suggested adds/renames.')
            ->addOption('discover-only', null, InputOption::VALUE_NONE, 'Only scan & print; do not write.')
            ->addOption('paths', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Limit discovery to these paths (relative to repo root).', [])
            ->addOption('strict', null, InputOption::VALUE_NONE, 'Treat warnings as errors.')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Emit machine-readable JSON report.')
            ->addOption('write', null, InputOption::VALUE_NONE, 'Write without confirmation (CI-safe with --non-interactive).')
            ->addOption('project-root', null, InputOption::VALUE_REQUIRED, 'Override project root (default: cwd).');
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Orchestrator
    // ─────────────────────────────────────────────────────────────────────────────
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = $this->styleFactory->create($input, $output);
        $opts = $this->gatherOptions($input);

        [$config, $firstRun] = $this->loadOrSeedConfig($opts['root']);

        if (($msgs = $this->validateSchema($config)) !== []) {
            $this->printIssues($io, $msgs);
            if ($opts['strict']) {
                $io->error('Strict mode: schema validation failed.');
                return 2;
            }
        }

        $def        = $this->defaults->resolve($config);
        $patterns   = (array) ($config['patterns'] ?? []);
        $targets    = (array) ($config['targets'] ?? []);
        $scanRoots = $this->determineRoots($patterns, $opts['root']);
        if ($firstRun && $patterns === []) {
            foreach ($scanRoots as $r) {
                // Add a single globstar pattern for that root
                $config['patterns'][] = ['match' => $r . '/**', 'include' => ['**']];
            }
        }

        $discPaths = [];
        foreach ($scanRoots as $r) {
            $found = $this->scanner->scan($opts['root'], $r, $opts['paths']);
            $discPaths = array_merge($discPaths, $found);
        }

        $discPaths = array_values(array_unique($discPaths));
        sort($discPaths);

        $groups     = $this->classifyAll(
            $opts['root'],
            $def,
            $patterns,
            $targets,
            $discPaths
        );

        // Summary counts
        foreach ($groups as $k => $items) {
            foreach ($items as $_) {
                $this->summary->inc($k);
            }
        }

        if ($opts['json']) {
            $defaultsForJson = [
                'repo_host' => $def['repo_host'],
                'repo_vendor' => $def['repo_vendor'],
                'repo_name_template' => $def['repo_name_template'],
                'default_repo_template' => $def['default_repo_template'],
            ];
            $output->write($this->jsonReporter->build($defaultsForJson, $groups, $this->buildActions($groups, $targets)));
            return 0;
        }

        $io->title('Chorale Setup');
        $this->renderHumanReport($io, $groups);

        if ($opts['discoverOnly']) {
            $io->success('Discovery only. No changes written.');
            return 4;
        }

        if ($opts['strict'] && ($groups['issues'] !== [] || $groups['conflicts'] !== [])) {
            $io->error('Strict mode: unresolved issues/conflicts.');
            return 2;
        }

        $actions = $this->buildActions($groups, $targets);
        if ($actions === []) {
            $tot = $this->summary->all();
            $io->writeln(sprintf(
                "No changes detected. • %d ok • %d new • %d renamed • %d drift • %d issues • %d conflicts",
                $tot['ok'] ?? 0,
                $tot['new'] ?? 0,
                $tot['renamed'] ?? 0,
                $tot['drift'] ?? 0,
                $tot['issues'] ?? 0,
                $tot['conflicts'] ?? 0
            ));
            $io->note('You can run this command as many times as you want. Run this after you create a new package.');
            return 0;
        }

        $io->section('Summary (to be written)');
        foreach ($actions as $a) {
            $io->writeln('- ' . $this->renderAction($a));
        }

        if (!$this->confirmWrite($io, $opts)) {
            $io->warning('Aborted. No changes written.');
            return 3;
        }

        $newConfig = $this->applyActions($config, $actions);
        $normalized = $this->configNormalizer->normalize($newConfig);
        $this->configWriter->write($opts['root'], $normalized);

        $io->success('Updated ./chorale.yaml');
        return 0;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Options / IO
    // ─────────────────────────────────────────────────────────────────────────────
    /** @return array{root:string,nonInteractive:bool,acceptAll:bool,discoverOnly:bool,strict:bool,json:bool,write:bool,paths:array<int,string>} */
    private function gatherOptions(InputInterface $input): array
    {
        $root = rtrim((string) ($input->getOption('project-root') ?: getcwd()), '/');

        return [
            'root'           => $root,
            'nonInteractive' => (bool) $input->getOption('non-interactive'),
            'acceptAll'      => (bool) $input->getOption('accept-all'),
            'discoverOnly'   => (bool) $input->getOption('discover-only'),
            'strict'         => (bool) $input->getOption('strict'),
            'json'           => (bool) $input->getOption('json'),
            'write'          => (bool) $input->getOption('write'),
            'paths'          => (array) $input->getOption('paths'),
        ];
    }

    /** @return array{array<string,mixed>, bool} [config, firstRun] */
    private function loadOrSeedConfig(string $root): array
    {
        $config = $this->configLoader->load($root);
        if ($config !== []) {
            return [$config, false];
        }

        // Seed minimal config on first run (patterns-only; no targets by default)
        return [[
            'version'                => 1,
            'repo_host'              => 'git@github.com',
            'repo_vendor'            => 'SonsOfPHP',
            'repo_name_template'     => '{name:kebab}.git',
            'default_repo_template'  => '{repo_host}:{repo_vendor}/{repo_name_template}',
            'default_branch'         => 'main',
            'splitter'               => 'splitsh',
            'tag_strategy'           => 'inherit-monorepo-tag',
            'rules' => [
                'keep_history'      => true,
                'skip_if_unchanged' => true,
                'require_files'     => ['composer.json', 'LICENSE'],
            ],
            //'patterns' => [
            //    ['match' => 'src/**', 'include' => ['**']],
            //],
            // 'targets' omitted by design
        ], true];
    }

    /** @return list<string> */
    private function validateSchema(array $config): array
    {
        // Keeping this simple (we already do type checks in SchemaValidator)
        return $this->schemaValidator->validate($config, 'tools/chorale/config/chorale.schema.yaml');
    }

    private function printIssues(SymfonyStyle $io, array $messages): void
    {
        foreach ($messages as $m) {
            $io->warning($m);
        }
    }

    private function confirmWrite(SymfonyStyle $io, array $opts): bool
    {
        if ($opts['write'] || $opts['acceptAll'] || $opts['nonInteractive']) {
            return true;
        }

        $helper = $this->getHelper('question');
        $confirm = new ConfirmationQuestion('Proceed? [Y/n] ', true);

        return (bool) $helper->ask($io->getInput(), $io->getOutput(), $confirm);
    }

    /** @return list<string> e.g. ["src","packages"] */
    private function determineRoots(array $patterns, string $root): array
    {
        if ($patterns !== []) {
            $roots = [];
            foreach ($patterns as $p) {
                $m = (string) ($p['match'] ?? '');
                if ($m === '') {
                    continue;
                }

                // root is first segment before slash
                $seg = explode('/', ltrim($m, '/'), 2)[0] ?? '';
                if ($seg !== '' && !in_array($seg, $roots, true)) {
                    $roots[] = $seg;
                }
            }

            return $roots !== [] ? $roots : ['src']; // safe fallback if patterns are odd
        }

        // First run: probe both src and packages
        $roots = [];
        foreach (['src','packages'] as $cand) {
            $any = $this->scanner->scan($root, $cand, []);
            if ($any !== []) {
                $roots[] = $cand;
            }
        }

        return $roots;
    }

    private function displayNameFor(string $projectRoot, string $pkgPath): string
    {
        $abs = rtrim($projectRoot, '/') . '/' . ltrim($pkgPath, '/');
        $meta = $this->composerMeta->read($abs);
        if (!empty($meta['name'])) {
            $name = (string) $meta['name'];
            // choose style: last segment after "/" for brevity
            $last = str_contains($name, '/') ? substr($name, strrpos($name, '/') + 1) : $name;
            return $last;
        }

        return basename($pkgPath);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Classification
    // ─────────────────────────────────────────────────────────────────────────────
    /**
     * @param array<string,mixed> $defaults
     * @param array<int, array<string,mixed>> $patterns
     * @param array<int, array<string,mixed>> $targets
     * @param list<string> $discovered
     * @return array{new:array<int,array>,renamed:array<int,array>,drift:array<int,array>,issues:array<int,array>,conflicts:array<int,array>,ok:array<int,array>}
     */
    private function classifyAll(string $root, array $defaults, array $patterns, array $targets, array $discovered): array
    {
        $byPath = [];
        foreach ($targets as $t) {
            $byPath[(string) $t['path']] = $t;
        }

        $groups = [
            'new' => [], 'renamed' => [], 'drift' => [], 'issues' => [], 'conflicts' => [], 'ok' => [],
        ];

        foreach ($discovered as $pkgPath) {
            $row = $this->classifyOne($root, $pkgPath, $defaults, $patterns, $byPath);
            $groups[$row['group']][] = $row['data'];
        }

        // Additionally, detect explicit-target renames where the old path no longer exists
        foreach ($byPath as $oldPath => $target) {
            if (!in_array($oldPath, $discovered, true)) {
                // If target points to a path that no longer exists but a new path with same identity does, propose rename
                $maybe = $this->findRenameTarget($oldPath, $target, $defaults, $patterns, $discovered);
                if ($maybe !== null) {
                    $groups['renamed'][] = $maybe;
                }
            }
        }

        return $groups;
    }

    /**
     * Classify a single discovered package path.
     * Rules:
     *  - If no explicit target & no matching pattern ⇒ NEW (config must change)
     *  - If pattern matches and no explicit target ⇒ OK (covered by pattern)
     *  - If explicit target exists ⇒ check issues/drift; else OK
     *
     * @return array{group:string,data:array<string,mixed>}
     */
    private function classifyOne(string $root, string $pkgPath, array $defaults, array $patterns, array $targetsByPath): array
    {
        $matches = $this->matcher->allMatches($patterns, $pkgPath);
        $pattern = $matches ? (array) $patterns[$matches[0]] : [];
        $hasExplicitTarget = isset($targetsByPath[$pkgPath]);

        $name = basename($pkgPath);
        $target = $targetsByPath[$pkgPath] ?? [];
        $repo = $this->resolver->resolve($defaults, $pattern, $target, $pkgPath, $name);

        $pkgName = $this->displayNameFor($root, $pkgPath);

        // Conflicts noted but don’t force NEW
        $conflictData = (count($matches) > 1) ? ['path' => $pkgPath, 'patterns' => $matches] : null;

        // No explicit target
        if (!$hasExplicitTarget) {
            if ($matches === []) {
                // Truly untracked: no pattern covers it
                return ['group' => 'new', 'data' => ['path' => $pkgPath, 'repo' => $repo, 'package' => $pkgName, 'reason' => 'no-pattern']];
            }

            // Covered by pattern → OK
            $ok = ['path' => $pkgPath, 'repo' => $repo, 'covered_by_pattern' => true, 'package' => $pkgName];
            if ($conflictData !== null && $conflictData !== []) {
                $ok['conflict'] = $conflictData['patterns'];
            }

            return ['group' => 'ok', 'data' => $ok];
        }

        // Explicit target exists → check required files + drift
        $missing = $this->requiredFiles->missing($root, $pkgPath, (array) $defaults['rules']['require_files']);
        if ($missing !== []) {
            return ['group' => 'issues', 'data' => ['path' => $pkgPath, 'missing' => $missing, 'package' => $pkgName]];
        }

        // Drift: if explicit `repo` template renders differently from resolved repo
        if (array_key_exists('repo', $target)) {
            $rendered = $this->resolver->resolve($defaults, $pattern, $target, $pkgPath, $name);
            if ($rendered !== $repo) {
                return ['group' => 'drift', 'data' => [
                    'path' => $pkgPath,
                    'package' => $pkgName,
                    'current' => ['repo' => $rendered],
                    'suggested' => ['repo' => $repo],
                ]];
            }
        }


        $ok = ['path' => $pkgPath, 'repo' => $repo, 'package' => $pkgName];
        if ($conflictData !== null && $conflictData !== []) {
            $ok['conflict'] = $conflictData['patterns'];
        }

        return ['group' => 'ok', 'data' => $ok];
    }

    /**
     * If an explicit target refers to a path that no longer exists, try to detect the new path by identity.
     * Returns a rename action payload or null.
     *
     * @param array<int, array<string,mixed>> $patterns
     * @param list<string> $discovered
     * @return array<string,mixed>|null
     */
    private function findRenameTarget(string $oldPath, array $target, array $defaults, array $patterns, array $discovered): ?array
    {
        $oldRepo = $this->resolver->resolve($defaults, $this->firstPatternFor($patterns, $oldPath), $target, $oldPath, basename($oldPath));
        $oldId = $this->identity->identityFor($oldPath, $oldRepo);

        foreach ($discovered as $newPath) {
            $pattern = $this->firstPatternFor($patterns, $newPath);
            $newRepo = $this->resolver->resolve($defaults, $pattern, [], $newPath, basename($newPath));
            if ($this->identity->identityFor($newPath, $newRepo) === $oldId) {
                return [
                    'from' => $oldPath,
                    'to'   => $newPath,
                    'repo_before' => $oldRepo,
                    'repo_after_suggested' => $newRepo,
                ];
            }
        }

        return null;
    }

    /** @param array<int, array<string,mixed>> $patterns */
    private function firstPatternFor(array $patterns, string $path): array
    {
        $idxs = $this->matcher->allMatches($patterns, $path);
        return $idxs ? (array) $patterns[$idxs[0]] : [];
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Actions
    // ─────────────────────────────────────────────────────────────────────────────
    /**
     * Build the set of changes to write:
     *  - add-target ONLY for true NEW (no pattern)
     *  - rename-target ONLY for explicit targets that moved
     *
     * @param array<string, array<int, array<string,mixed>>> $groups
     * @param array<int, array<string,mixed>> $existingTargets
     * @return array<int, array<string,mixed>>
     */
    private function buildActions(array $groups, array $existingTargets): array
    {
        // Build a quick lookup of explicit targets
        $byPath = [];
        foreach ($existingTargets as $t) {
            $byPath[(string) $t['path']] = true;
        }

        $actions = [];

        foreach ($groups['new'] as $row) {
            // New only occurs when no pattern covers it → we must add a target (or new pattern, future)
            $actions[] = ['type' => 'add-target', 'path' => $row['path'], 'name' => basename((string) $row['path'])];
        }

        foreach ($groups['renamed'] as $row) {
            // Only apply rename if the old path is an explicit target
            if (!empty($byPath[(string) $row['from']])) {
                $actions[] = ['type' => 'rename-target', 'from' => $row['from'], 'to' => $row['to']];
            }
        }

        return $actions;
    }

    /** @param array<string,mixed> $action */
    private function renderAction(array $action): string
    {
        return match ($action['type']) {
            'add-target'    => sprintf('Add target: %s', $action['path']),
            'rename-target' => sprintf('Update target path: %s → %s', $action['from'], $action['to']),
            default         => 'Unknown action',
        };
    }

    /**
     * Apply actions to config (pure transform).
     * @param array<string,mixed> $config
     * @param array<int, array<string,mixed>> $actions
     * @return array<string,mixed>
     */
    private function applyActions(array $config, array $actions): array
    {
        $targets = (array) ($config['targets'] ?? []);

        foreach ($actions as $a) {
            if ($a['type'] === 'add-target') {
                $targets[] = [
                    'name'    => (string) $a['name'],
                    'path'    => (string) $a['path'],
                    'include' => ['**'],
                    // no overrides; patterns handle repo computation
                ];
            } elseif ($a['type'] === 'rename-target') {
                foreach ($targets as &$t) {
                    if (($t['path'] ?? '') === (string) $a['from']) {
                        $t['path'] = (string) $a['to'];
                        break;
                    }
                }

                unset($t);
            }
        }

        if ($targets !== []) {
            $config['targets'] = $targets;
        } else {
            unset($config['targets']);
        }

        return $config;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Reporting
    // ─────────────────────────────────────────────────────────────────────────────
    private function renderHumanReport(SymfonyStyle $io, array $groups): void
    {
        $io->section('Auto-discovery (src/)');

        $this->printGroup($io, 'OK', $groups['ok'], function (array $r): string {
            $suffix = empty($r['covered_by_pattern']) ? '' : ' (pattern)';
            if (!empty($r['conflict'])) {
                $suffix .= sprintf(' (conflict: patterns %s)', implode(',', (array) $r['conflict']));
            }

            return sprintf('%s%s', $r['package'], $suffix);
        });

        $this->printGroup($io, 'NEW', $groups['new'], fn(array $r): string => sprintf('%s → %s (no matching pattern)', $r['path'], $r['repo']));
        $this->printGroup($io, 'RENAMED', $groups['renamed'], fn(array $r): string => sprintf('%s → %s', $r['from'], $r['to']));
        $this->printGroup($io, 'DRIFT', $groups['drift'], fn(array $r) => $r['path']);
        $this->printGroup($io, 'ISSUES', $groups['issues'], fn(array $r): string => sprintf('%s (missing: %s)', $r['path'], implode(', ', (array) ($r['missing'] ?? []))));
        $this->printGroup($io, 'CONFLICTS', $groups['conflicts'], fn(array $r): string => sprintf('%s (patterns: %s)', $r['path'], implode(', ', (array) ($r['patterns'] ?? []))));
    }

    private function printGroup(SymfonyStyle $io, string $title, array $rows, callable $fmt): void
    {
        if ($rows === []) {
            return;
        }

        $io->writeln(sprintf('<info>%s</info>', $title));
        foreach ($rows as $r) {
            $io->writeln('  • ' . $fmt($r));
        }

        $io->newLine();
    }
}
