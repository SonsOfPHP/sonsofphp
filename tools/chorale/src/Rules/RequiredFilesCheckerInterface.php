<?php

declare(strict_types=1);

namespace Chorale\Rules;

interface RequiredFilesCheckerInterface
{
    /**
     * Check required files exist under a package path.
     * @param list<string> $required relative file names like ["composer.json","LICENSE"]
     * @return list<string> missing file names
     */
    public function missing(string $projectRoot, string $packagePath, array $required): array;
}
