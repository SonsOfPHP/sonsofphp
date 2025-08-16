<?php

declare(strict_types=1);

namespace Chorale\Plan;

use Chorale\Composer\ComposerJsonReaderInterface;
use Chorale\Composer\DependencyMergerInterface;
use Chorale\Composer\RuleEngineInterface;
use Chorale\Config\ConfigDefaultsInterface;
use Chorale\Discovery\PackageScannerInterface;
use Chorale\Discovery\PatternMatcherInterface;
use Chorale\Repo\RepoResolverInterface;
use Chorale\Split\SplitDeciderInterface;
use Chorale\Util\DiffUtilInterface;
use Chorale\Util\PathUtilsInterface;

final readonly class PlanBuilder implements PlanBuilderInterface
{
    public function __construct(
        private ConfigDefaultsInterface $defaults,
        private PackageScannerInterface $scanner,
        private PatternMatcherInterface $matcher,
        private RepoResolverInterface $resolver,
        private PathUtilsInterface $paths,
        private ComposerJsonReaderInterface $composerReader,
        private DependencyMergerInterface $depMerger,
        private RuleEngineInterface $ruleEngine,
        private SplitDeciderInterface $splitDecider,
        private DiffUtilInterface $diffs,
    ) {}

    public function build(string $projectRoot, array $config, array $options = []): array
    {
        $opts = [
            'paths'         => (array) ($options['paths'] ?? []),
            'show_all'      => (bool) ($options['show_all'] ?? false),
            'force_split'   => (bool) ($options['force_split'] ?? false),
            'verify_remote' => (bool) ($options['verify_remote'] ?? false),
            'strict'        => (bool) ($options['strict'] ?? false),
        ];

        $exit = 0;
        $def  = $this->defaults->resolve($config);
        $patterns = (array) ($config['patterns'] ?? []);
        $targets  = (array) ($config['targets'] ?? []);
        $targetsByPath = [];
        foreach ($targets as $t) {
            $targetsByPath[(string) $t['path']] = $t;
        }

        // Read root composer.json (name/version and current require maps)
        $rootComposer = $this->composerReader->read($projectRoot . '/composer.json');
        $rootVersion  = is_string($rootComposer['version'] ?? null) ? $rootComposer['version'] : null;
        $rootName     = is_string($rootComposer['name'] ?? null) ? strtolower($rootComposer['name']) : null;
        if ($rootName === null) {
            $rootName = strtolower($def['repo_vendor'] . '/' . $def['repo_vendor']);
        }

        // Discover packages based on patterns roots
        $roots = $this->rootsFromPatterns($patterns) !== [] ? $this->rootsFromPatterns($patterns) : ['src'];
        $discovered = [];
        foreach ($roots as $r) {
            $discovered = array_merge($discovered, $this->scanner->scan($projectRoot, $r, $opts['paths']));
        }

        $discovered = array_values(array_unique($discovered));
        sort($discovered);

        $steps = [];
        $noop  = [
            'version'   => [],
            'metadata'  => [],
            'split'     => [],
            'root-agg'  => [],
            'root-merge' => [],
        ];

        // Collect package names and per-package diffs
        $packageNames = []; // path => full composer name
        foreach ($discovered as $pkgPath) {
            $matches = $this->matcher->allMatches($patterns, $pkgPath);
            if ($matches === []) {
                // not covered by any pattern → out of scope
                continue;
            }

            $pattern = (array) $patterns[$matches[0]];
            $nameLeaf = $this->paths->leaf($pkgPath);
            $target  = $targetsByPath[$pkgPath] ?? [];
            $repo    = $this->resolver->resolve($def, $pattern, $target, $pkgPath, $nameLeaf);

            // Composer name (prefer composer.json)
            $pcJson = $this->composerReader->read($projectRoot . '/' . $pkgPath . '/composer.json');
            $pkgName = is_string($pcJson['name'] ?? null) ? strtolower($pcJson['name']) : strtolower($def['repo_vendor'] . '/' . $this->paths->kebab($nameLeaf));
            $packageNames[$pkgPath] = $pkgName;

            // 1) Version sync
            if (is_string($rootVersion) && $rootVersion !== '') {
                $current = is_string($pcJson['version'] ?? null) ? $pcJson['version'] : null;
                if ($current !== $rootVersion) {
                    $reason = $current === null ? 'missing' : 'mismatch';
                    $steps[] = new PackageVersionUpdateStep($pkgPath, $pkgName, $rootVersion, $reason);
                } elseif ($opts['show_all']) {
                    $noop['version'][] = $pkgName;
                }
            } elseif ($opts['strict']) {
                $exit = $exit !== 0 ? $exit : 1; // missing root version in strict mode
            }

            // 2) Metadata sync (compute desired vs current using rule engine)
            $overrides = $this->collectOverrides($pattern, $target);
            $apply = $this->ruleEngine->computePackageEdits($pcJson, $rootComposer, $config, [
                'path'      => $pkgPath,
                'name'      => $pkgName,
                'overrides' => $overrides,
            ]);
            if ($apply !== []) {
                $overKeys = $this->extractOverrideKeys($apply);
                $apply = $this->stripInternalMarkers($apply);

                $steps[] = new PackageMetadataSyncStep($pkgPath, $pkgName, $apply, $overrides);
            } elseif ($opts['show_all']) {
                $noop['metadata'][] = $pkgName;
            }

            // 3) Split necessity (content/remote/policy)
            $splitReasons = $this->splitDecider->reasonsToSplit($projectRoot, $pkgPath, [
                'force_split' => $opts['force_split'],
                'verify_remote' => $opts['verify_remote'],
                'repo' => $repo,
                'branch' => (string) $def['default_branch'],
                'tag_strategy' => (string) $def['tag_strategy'],
                'ignore' => (array) ($config['split']['ignore'] ?? ['vendor/**','**/composer.lock','**/.DS_Store']),
            ]);
            if ($splitReasons !== []) {
                $steps[] = new SplitStep(
                    path: $pkgPath,
                    name: $nameLeaf,
                    repo: $repo,
                    branch: (string) $def['default_branch'],
                    splitter: (string) $def['splitter'],
                    tagStrategy: (string) $def['tag_strategy'],
                    keepHistory: (bool) ($def['rules']['keep_history'] ?? true),
                    skipIfUnchanged: (bool) ($def['rules']['skip_if_unchanged'] ?? true),
                    reasons: $splitReasons
                );
            } elseif ($opts['show_all']) {
                $noop['split'][] = $pkgName;
            }
        }

        // 4) Root aggregator (require/replace all packages at rootVersion)
        $aggStep = null;
        if ($packageNames !== []) {
            $require = [];
            $replace = [];
            foreach ($packageNames as $pkgFull) {
                if ($pkgFull === $rootName) {
                    continue;
                }

                $ver = $rootVersion ?? '*';
                $require[$pkgFull] = $ver;
                $replace[$pkgFull] = $ver;
            }

            // Compare with current root (only add if it would change)
            $desired = ['require' => $require, 'replace' => $replace, 'root' => $rootName, 'root_version' => $rootVersion];
            $current = [
                'require' => (array) ($rootComposer['require'] ?? []),
                'replace' => (array) ($rootComposer['replace'] ?? []),
                'root'    => (string) ($rootComposer['name'] ?? $rootName),
                'root_version' => (string) ($rootComposer['version'] ?? ($rootVersion ?? '')),
            ];
            if ($this->diffs->changed($current, $desired, ['require','replace','root','root_version'])) {
                $aggStep = new ComposerRootUpdateStep($rootName, $rootVersion, $require, $replace, ['version_strategy' => 'lockstep-root']);
                $steps[] = $aggStep;
            } elseif ($opts['show_all']) {
                $noop['root-agg'][] = 'composer.json';
            }
        }

        // 5) Root dependency merge (strategy engine)
        $merge = $this->depMerger->computeRootMerge($projectRoot, array_keys($packageNames), [
            'strategy_require' => (string) ($config['composer_sync']['merge_strategy']['require'] ?? 'union-caret'),
            'strategy_require_dev' => (string) ($config['composer_sync']['merge_strategy']['require-dev'] ?? 'union-caret'),
            'exclude_monorepo_packages' => true,
            'monorepo_names' => array_values($packageNames),
        ]);
        if (!empty($merge['require']) || !empty($merge['require-dev']) || !empty($merge['conflicts'])) {
            // Compare with current
            $current = [
                'require'     => (array) ($rootComposer['require'] ?? []),
                'require-dev' => (array) ($rootComposer['require-dev'] ?? []),
            ];
            $desired = [
                'require'     => (array) ($merge['require'] ?? []),
                'require-dev' => (array) ($merge['require-dev'] ?? []),
            ];
            if ($this->diffs->changed($current, $desired, ['require','require-dev'])) {
                $steps[] = new RootDependencyMergeStep(
                    (array) $merge['require'],
                    (array) $merge['require-dev'],
                    (array) ($merge['conflicts'] ?? [])
                );
                if (!empty($merge['conflicts']) && $opts['strict']) {
                    $exit = $exit !== 0 ? $exit : 1;
                }
            } elseif ($opts['show_all']) {
                $noop['root-merge'][] = 'composer.json';
            }
        }

        // 6) Root rebuild (only if prior steps exist)
        if ($steps !== []) {
            $steps[] = new ComposerRootRebuildStep(['validate']); // add 'normalize' later if desired
        }

        return [
            'steps'     => $steps,
            'noop'      => $noop,
            'exit_code' => $exit,
        ];
    }

    /** @param array<int,array<string,mixed>> $patterns @return list<string> */
    private function rootsFromPatterns(array $patterns): array
    {
        $roots = [];
        foreach ($patterns as $p) {
            $m = (string) ($p['match'] ?? '');
            if ($m === '') {
                continue;
            }

            $seg = explode('/', ltrim($m, '/'), 2)[0] ?? '';
            if ($seg !== '' && !in_array($seg, $roots, true)) {
                $roots[] = $seg;
            }
        }

        return $roots;
    }

    /** @return array{values:array<string,mixed>,rules:array<string,string>} */
    private function collectOverrides(array $pattern, array $target): array
    {
        $p = (array) ($pattern['composer_overrides'] ?? []);
        $t = (array) ($target['composer_overrides'] ?? []);
        $values = array_merge((array) ($p['values'] ?? []), (array) ($t['values'] ?? []));
        $rules  = array_merge((array) ($p['rules'] ?? []), (array) ($t['rules'] ?? []));
        return ['values' => $values, 'rules' => $rules];
    }

    /** @param array<string,mixed> $apply @return list<string> */
    private function extractOverrideKeys(array $apply): array
    {
        $keys = [];
        foreach ($apply as $k => $v) {
            if (is_array($v) && array_key_exists('__override', $v) && $v['__override'] === true) {
                $keys[] = (string) $k;
            }
        }

        return $keys;
    }

    /** @param array<string,mixed> $apply @return array<string,mixed> */
    private function stripInternalMarkers(array $apply): array
    {
        foreach ($apply as $k => $v) {
            if (is_array($v) && array_key_exists('__override', $v)) {
                unset($apply[$k]['__override']);
            }
        }

        return $apply;
    }
}
