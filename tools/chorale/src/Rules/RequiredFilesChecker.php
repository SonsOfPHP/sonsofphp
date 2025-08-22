<?php

declare(strict_types=1);

namespace Chorale\Rules;

final class RequiredFilesChecker implements RequiredFilesCheckerInterface
{
    public function missing(string $projectRoot, string $packagePath, array $required): array
    {
        $root = rtrim($projectRoot, '/');
        $pkg  = $root . '/' . ltrim($packagePath, '/');

        $miss = [];
        foreach ($required as $rel) {
            $f = $pkg . '/' . ltrim($rel, '/');
            if (!is_file($f)) {
                $miss[] = $rel;
            }
        }

        return $miss;
    }
}
