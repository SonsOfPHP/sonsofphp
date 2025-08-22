<?php

declare(strict_types=1);

namespace Chorale\Discovery;

interface ComposerMetadataInterface
{
    /** @return array{name?:string} Parsed subset; empty array if composer.json missing/invalid */
    public function read(string $absolutePackageDir): array;
}
