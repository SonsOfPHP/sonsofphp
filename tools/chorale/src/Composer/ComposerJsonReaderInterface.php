<?php

declare(strict_types=1);

namespace Chorale\Composer;

interface ComposerJsonReaderInterface
{
    /** @return array<string,mixed> {} if missing/invalid */
    public function read(string $absolutePath): array;
}
