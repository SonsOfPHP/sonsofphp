<?php

declare(strict_types=1);

namespace Chorale\Composer;

final readonly class DependencyMerger implements DependencyMergerInterface
{
    public function __construct(
        private readonly ComposerJsonReaderInterface $reader
    ) {}

    public function computeRootMerge(string $projectRoot, array $packagePaths, array $options = []): array
    {
        $normalizedOptions = [
            'strategy_require'        => (string) ($options['strategy_require'] ?? 'union-caret'),
            'strategy_require_dev'    => (string) ($options['strategy_require-dev'] ?? 'union-caret'),
            'exclude_monorepo_packages' => (bool) ($options['exclude_monorepo_packages'] ?? true),
            'monorepo_names'          => (array) ($options['monorepo_names'] ?? []),
        ];

        $monorepoNames = array_map('strtolower', array_values($normalizedOptions['monorepo_names']));

        $requiredDependencies = [];
        $devDependencies      = [];
        $constraintsByDependency = [
            'require'     => [],
            'require-dev' => [],
        ];

        foreach ($packagePaths as $relativePath) {
            $composerJson = $this->reader->read(rtrim($projectRoot, '/') . '/' . $relativePath . '/composer.json');
            if ($composerJson === []) {
                continue;
            }

            $packageName = strtolower((string) ($composerJson['name'] ?? $relativePath));
            foreach ((array) ($composerJson['require'] ?? []) as $dependency => $version) {
                if (!is_string($dependency) || !is_string($version)) {
                    continue;
                }

                if ($normalizedOptions['exclude_monorepo_packages'] && in_array(strtolower($dependency), $monorepoNames, true)) {
                    continue;
                }

                $constraintsByDependency['require'][$dependency][$packageName] = $version;
            }

            foreach ((array) ($composerJson['require-dev'] ?? []) as $dependency => $version) {
                if (!is_string($dependency) || !is_string($version)) {
                    continue;
                }

                if ($normalizedOptions['exclude_monorepo_packages'] && in_array(strtolower($dependency), $monorepoNames, true)) {
                    continue;
                }

                $constraintsByDependency['require-dev'][$dependency][$packageName] = $version;
            }
        }

        $conflicts = [];
        $requiredDependencies = $this->mergeMap($constraintsByDependency['require'], $normalizedOptions['strategy_require'], $conflicts);
        $devDependencies      = $this->mergeMap($constraintsByDependency['require-dev'], $normalizedOptions['strategy_require_dev'], $conflicts);

        ksort($requiredDependencies);
        ksort($devDependencies);

        return [
            'require'     => $requiredDependencies,
            'require-dev' => $devDependencies,
            'conflicts'   => array_values($conflicts),
        ];
    }

    /**
     * @param array<string,array<string,string>> $constraintsPerDependency
     * @param array<string,array<string,mixed>> $conflictsOut
     * @return array<string,string>
     */
    private function mergeMap(array $constraintsPerDependency, string $strategy, array &$conflictsOut): array
    {
        $mergedConstraints = [];
        foreach ($constraintsPerDependency as $dependency => $versionsByPackage) {
            $constraint = $this->chooseConstraint(
                array_values($versionsByPackage),
                $strategy,
                $dependency,
                $versionsByPackage,
                $conflictsOut
            );
            if ($constraint !== null) {
                $mergedConstraints[$dependency] = $constraint;
            }
        }

        return $mergedConstraints;
    }

    /**
     * @param list<string> $constraints
     * @param array<string,string> $versionsByPackage
     */
    private function chooseConstraint(array $constraints, string $strategy, string $dependency, array $versionsByPackage, array &$conflictsOut): ?string
    {
        $strategy = strtolower($strategy);
        $normalized = array_map([$this,'normalizeConstraint'], array_filter($constraints, 'is_string'));
        if ($normalized === []) {
            return null;
        }

        if ($strategy === 'union-caret') {
            return $this->chooseUnionCaret($normalized, $dependency, $versionsByPackage, $conflictsOut);
        }

        if ($strategy === 'union-loose') {
            return '*';
        }

        if ($strategy === 'max') {
            return $this->maxLowerBound($normalized);
        }

        if ($strategy === 'intersect') {
            // naive: if all share same major series, pick max lower bound; else conflict
            $majorVersions = array_unique(array_map(static fn($c): int => $c['major'], $normalized));
            if (count($majorVersions) > 1) {
                $this->recordConflict($dependency, $versionsByPackage, $conflictsOut, 'intersect-empty');
                return null;
            }

            return $this->maxLowerBound($normalized);
        }

        // default fallback
        return $this->chooseUnionCaret($normalized, $dependency, $versionsByPackage, $conflictsOut);
    }

    /** @param list<array{raw:string,major:int,minor:int,patch:int,type:string}> $norm */
    private function chooseUnionCaret(array $norm, string $dependency, array $versionsByPackage, array &$conflictsOut): string
    {
        // Prefer highest ^MAJOR.MINOR; if any non-caret constraints exist, record a conflict and still pick a sane default.
        $caret = array_values(array_filter($norm, static fn($c): bool => $c['type'] === 'caret'));
        if ($caret !== []) {
            usort($caret, [$this,'cmpSemver']);
            $best = end($caret);
            if (count($caret) !== count($norm)) {
                $this->recordConflict($dependency, $versionsByPackage, $conflictsOut, 'non-caret-mixed');
            }

            return '^' . $best['major'] . '.' . $best['minor'];
        }

        // If exact pins or ranges exist, pick the "max lower bound" and record conflict
        $this->recordConflict($dependency, $versionsByPackage, $conflictsOut, 'non-caret-mixed');
        return $this->maxLowerBound($norm);
    }

    /** @param list<array{raw:string,major:int,minor:int,patch:int,type:string}> $norm */
    private function maxLowerBound(array $norm): string
    {
        usort($norm, [$this,'cmpSemver']);
        $best = end($norm);
        if ($best['type'] === 'caret') {
            return '^' . $best['major'] . '.' . $best['minor'];
        }

        // fallback to exact lower bound
        return $best['raw'];
    }

    /** @param array<string,string> $versionsByPackage */
    private function recordConflict(string $dependency, array $versionsByPackage, array &$conflictsOut, string $reason): void
    {
        $conflictsOut[$dependency] = [
            'package'   => $dependency,
            'versions'  => array_values(array_unique(array_values($versionsByPackage))),
            'packages'  => array_keys($versionsByPackage),
            'reason'    => $reason,
        ];
    }

    /** @return array{raw:string,major:int,minor:int,patch:int,type:string} */
    private function normalizeConstraint(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '*') {
            return ['raw' => '*', 'major' => 0, 'minor' => 0, 'patch' => 0, 'type' => 'wild'];
        }

        if ($raw[0] === '^') {
            $v = substr($raw, 1);
            [$M,$m,$p] = $this->parseSemver($v);
            return ['raw' => '^' . $M . '.' . $m, 'major' => $M, 'minor' => $m, 'patch' => $p, 'type' => 'caret'];
        }

        // naive parse: try to get leading semver numbers
        [$M,$m,$p] = $this->parseSemver($raw);
        return ['raw' => $M . '.' . $m . '.' . $p, 'major' => $M, 'minor' => $m, 'patch' => $p, 'type' => 'pin'];
    }

    /** @return array{0:int,1:int,2:int} */
    private function parseSemver(string $raw): array
    {
        $raw = ltrim($raw, 'vV');
        $parts = preg_split('/[^\d]+/', $raw);
        $M = (int) ($parts[0] ?? 0);
        $m = (int) ($parts[1] ?? 0);
        $p = (int) ($parts[2] ?? 0);
        return [$M,$m,$p];
    }

    /** @param array{major:int,minor:int,patch:int} $a @param array{major:int,minor:int,patch:int} $b */
    private function cmpSemver(array $a, array $b): int
    {
        return [$a['major'],$a['minor'],$a['patch']] <=> [$b['major'],$b['minor'],$b['patch']];
    }
}
