<?php

declare(strict_types=1);

namespace Chorale\Composer;

interface ComposerJsonReaderInterface
{
    /**
     * @return array<string, mixed>
     *   if missing/invalid, it will return an empty array
     */
    public function read(string $absolutePath): array;
}
