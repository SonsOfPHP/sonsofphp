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

        $opts = [
            'strategy_require' => (string) ($options['strategy_require'] ?? 'union-caret'),
            'strategy_require_dev' => (string) ($options['strategy_require-dev'] ?? 'union-caret'),
            'exclude_monorepo_packages' => (bool) ($options['exclude_monorepo_packages'] ?? true),
            'monorepo_names' => (array) ($options['monorepo_names'] ?? []),
        ];

        $monorepo = array_map('strtolower', array_values($opts['monorepo_names']));

        $reqs = [];
        $devs = [];
        $byDepConstraints = [
            'require' => [],
            'require-dev' => [],
        ];

        foreach ($packagePaths as $relPath) {
            $pc = $this->reader->read(rtrim($projectRoot, '/') . '/' . $relPath . '/composer.json');
            if ($pc === []) {
                continue;
            }

            $name = strtolower((string) ($pc['name'] ?? $relPath));
            foreach ((array) ($pc['require'] ?? []) as $dep => $ver) {
                if (!is_string($dep)) {
                    continue;
                }

                if (!is_string($ver)) {
                    continue;
                }

                if ($opts['exclude_monorepo_packages'] && in_array(strtolower($dep), $monorepo, true)) {
                    continue;
                }

                $byDepConstraints['require'][$dep][$name] = $ver;
            }

            foreach ((array) ($pc['require-dev'] ?? []) as $dep => $ver) {
                if (!is_string($dep)) {
                    continue;
                }

                if (!is_string($ver)) {
                    continue;
                }

                if ($opts['exclude_monorepo_packages'] && in_array(strtolower($dep), $monorepo, true)) {
                    continue;
                }

                $byDepConstraints['require-dev'][$dep][$name] = $ver;
            }
        }

        $conflicts = [];
        $reqs = $this->mergeMap($byDepConstraints['require'], $opts['strategy_require'], $conflicts);
        $devs = $this->mergeMap($byDepConstraints['require-dev'], $opts['strategy_require_dev'], $conflicts);

        ksort($reqs);
        ksort($devs);

        return [
            'require'     => $reqs,
            'require-dev' => $devs,
            'conflicts'   => array_values($conflicts),
        ];
    }

    /**
     * @param array<string,array<string,string>> $constraintsPerDep
     * @param array<string,array<string,mixed>> $conflictsOut
     * @return array<string,string>
     */
    private function mergeMap(array $constraintsPerDep, string $strategy, array &$conflictsOut): array
    {
        $out = [];
        foreach ($constraintsPerDep as $dep => $byPkg) {
            $constraint = $this->chooseConstraint(array_values($byPkg), $strategy, $dep, $byPkg, $conflictsOut);
            if ($constraint !== null) {
                $out[$dep] = $constraint;
            }
        }

        return $out;
    }

    /**
     * @param list<string> $constraints
     * @param array<string,string> $byPkg
     */
    private function chooseConstraint(array $constraints, string $strategy, string $dep, array $byPkg, array &$conflictsOut): ?string
    {
        $strategy = strtolower($strategy);
        $norm = array_map([$this,'normalizeConstraint'], array_filter($constraints, 'is_string'));
        if ($norm === []) {
            return null;
        }

        if ($strategy === 'union-caret') {
            return $this->chooseUnionCaret($norm, $dep, $byPkg, $conflictsOut);
        }

        if ($strategy === 'union-loose') {
            return '*';
        }

        if ($strategy === 'max') {
            return $this->maxLowerBound($norm);
        }

        if ($strategy === 'intersect') {
            // naive: if all share same major series, pick max lower bound; else conflict
            $majors = array_unique(array_map(static fn($c): int => $c['major'], $norm));
            if (count($majors) > 1) {
                $this->recordConflict($dep, $byPkg, $conflictsOut, 'intersect-empty');
                return null;
            }

            return $this->maxLowerBound($norm);
        }

        // default fallback
        return $this->chooseUnionCaret($norm, $dep, $byPkg, $conflictsOut);
    }

    /** @param list<array{raw:string,major:int,minor:int,patch:int,type:string}> $norm */
    private function chooseUnionCaret(array $norm, string $dep, array $byPkg, array &$conflictsOut): string
    {
        // Prefer highest ^MAJOR.MINOR; if any non-caret constraints exist, record a conflict and still pick a sane default.
        $caret = array_values(array_filter($norm, static fn($c): bool => $c['type'] === 'caret'));
        if ($caret !== []) {
            usort($caret, [$this,'cmpSemver']);
            $best = end($caret);
            return '^' . $best['major'] . '.' . $best['minor'];
        }

        // If exact pins or ranges exist, pick the "max lower bound" and record conflict
        $this->recordConflict($dep, $byPkg, $conflictsOut, 'non-caret-mixed');
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

    /** @param array<string,string> $byPkg */
    private function recordConflict(string $dep, array $byPkg, array &$conflictsOut, string $reason): void
    {
        $conflictsOut[$dep] = [
            'package'   => $dep,
            'versions'  => array_values(array_unique(array_values($byPkg))),
            'packages'  => array_keys($byPkg),
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
