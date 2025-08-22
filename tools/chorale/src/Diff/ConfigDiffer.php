<?php

declare(strict_types=1);

namespace Chorale\Diff;

use Chorale\Config\ConfigDefaultsInterface;
use Chorale\Discovery\PackageIdentityInterface;
use Chorale\Discovery\PatternMatcherInterface;
use Chorale\Repo\RepoResolverInterface;
use Chorale\Rules\RequiredFilesCheckerInterface;
use Chorale\Util\PathUtilsInterface;

/**
 * Computes differences between discovered packages and the current config.
 *
 * Groups results into: new, renamed, drift, issues, conflicts, ok.
 *
 * Example:
 * - diff($config, ['src/Acme/Foo'], []) may return ['new' => [['path'=>'src/Acme/Foo', 'repo'=>'git@...']]]
 */
final readonly class ConfigDiffer implements ConfigDifferInterface
{
    public function __construct(
        private ConfigDefaultsInterface $defaults,
        private PatternMatcherInterface $matcher,
        private RepoResolverInterface $resolver,
        private PackageIdentityInterface $identity,
        private RequiredFilesCheckerInterface $requiredFiles,
        private PathUtilsInterface $paths
    ) {}

    /**
     * @param array<string,mixed> $config     Full configuration array
     * @param list<string>        $discovered Discovered package paths (relative)
     * @param array<string,mixed> $context    Reserved for future extension
     * @return array<string, array<int, array<string,mixed>>> Grouped diff results
     */
    public function diff(array $config, array $discovered, array $context): array
    {
        $def = $this->defaults->resolve($config);
        $patterns = (array) ($config['patterns'] ?? []);
        $targets  = (array) ($config['targets'] ?? []);

        // Build quick lookups
        $targetsByPath = [];
        foreach ($targets as $t) {
            $targetsByPath[(string) $t['path']] = $t;
        }

        $groups = [
            'new'       => [],
            'renamed'   => [],
            'drift'     => [],
            'issues'    => [],
            'conflicts' => [],
            'ok'        => [],
        ];

        foreach ($discovered as $pkgPath) {
            $matchIdxs = $this->matcher->allMatches($patterns, $pkgPath);
            $pattern = $matchIdxs !== [] ? (array) $patterns[$matchIdxs[0]] : [];
            $target  = $targetsByPath[$pkgPath] ?? [];

            $name    = $this->paths->leaf($pkgPath);
            $repo    = $this->resolver->resolve($def, $pattern, $target, $pkgPath, $name);

            // conflicts?
            if (count($matchIdxs) > 1) {
                $groups['conflicts'][] = [
                    'path' => $pkgPath,
                    'patterns' => $matchIdxs,
                ];
                // continue; we still classify but show conflict
            }

            $existsInConfig = isset($targetsByPath[$pkgPath]);
            if (!$existsInConfig) {
                // try rename detection: see if any configured target shares identity
                $id = $this->identity->identityFor($pkgPath, $repo);
                $renamedFrom = null;
                foreach ($targetsByPath as $p => $t) {
                    $oldRepo = $this->resolver->resolve($def, $this->findPatternFor($patterns, $p), $t, $p, $this->paths->leaf($p));
                    if ($this->identity->identityFor($p, $oldRepo) === $id) {
                        $renamedFrom = $p;
                        break;
                    }
                }

                if ($renamedFrom !== null) {
                    $groups['renamed'][] = [
                        'from' => $renamedFrom,
                        'to'   => $pkgPath,
                        'repo_before' => $this->resolver->resolve($def, $this->findPatternFor($patterns, $renamedFrom), $targetsByPath[$renamedFrom], $renamedFrom, $this->paths->leaf($renamedFrom)),
                        'repo_after_suggested' => $repo,
                    ];
                    continue;
                }

                $groups['new'][] = [
                    'path'   => $pkgPath,
                    'repo'   => $repo,
                    'pattern' => $matchIdxs[0] ?? null,
                ];
                continue;
            }

            // drift: compare rendered repo vs stored overrides (if any)
            $current = $targetsByPath[$pkgPath];
            $curRepo = $this->resolver->resolve($def, $pattern, $current, $pkgPath, $name);
            $driftFields = [];
            foreach (['repo_host','repo_vendor','repo_name_template','repo'] as $k) {
                if (array_key_exists($k, $current)) {
                    $scope = $current[$k];
                    $expected = $pattern[$k] ?? $def[$k] ?? null;
                    if ($k === 'repo' && $scope !== null) {
                        // explicit template; compare rendered values instead
                        if ($curRepo !== $repo) {
                            $driftFields['repo'] = ['from' => $curRepo, 'to' => $repo];
                        }

                        continue;
                    }

                    if ($expected !== null && (string) $scope === (string) $expected) {
                        // redundant override; suggest removing by reporting drift
                        $driftFields[$k] = ['from' => $scope, 'to' => $expected];
                    }
                }
            }

            // issues: required files
            $missing = $this->requiredFiles->missing(
                '.',
                getcwd() !== false ? getcwd() . '/' . $pkgPath : $pkgPath,
                (array) $def['rules']['require_files']
            );
            if ($missing !== []) {
                $groups['issues'][] = ['path' => $pkgPath, 'missing' => $missing];
            }

            if ($driftFields !== []) {
                $groups['drift'][] = [
                    'path' => $pkgPath,
                    'current' => [
                        'repo_host' => $current['repo_host'] ?? null,
                        'repo_vendor' => $current['repo_vendor'] ?? null,
                        'repo_name_template' => $current['repo_name_template'] ?? null,
                        'repo' => $current['repo'] ?? null,
                    ],
                    'suggested' => [
                        'repo_host' => $pattern['repo_host'] ?? $def['repo_host'],
                        'repo_vendor' => $pattern['repo_vendor'] ?? $def['repo_vendor'],
                        'repo_name_template' => $pattern['repo_name_template'] ?? $def['repo_name_template'],
                        'repo' => $pattern['repo'] ?? null,
                    ],
                ];
                continue;
            }

            if (count($matchIdxs) > 1) {
                // Still OK but with conflict noted
                $groups['conflicts'][] = ['path' => $pkgPath, 'patterns' => $matchIdxs];
            } else {
                $groups['ok'][] = ['path' => $pkgPath, 'repo' => $repo];
            }
        }

        return $groups;
    }

    /** @param array<int, array<string,mixed>> $patterns */
    private function findPatternFor(array $patterns, string $path): array
    {
        $idxs = $this->matcher->allMatches($patterns, $path);
        return $idxs !== [] ? (array) $patterns[$idxs[0]] : [];
    }
}
