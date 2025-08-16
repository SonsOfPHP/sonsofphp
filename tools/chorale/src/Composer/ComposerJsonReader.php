<?php

declare(strict_types=1);

namespace Chorale\Composer;

final class ComposerJsonReader implements ComposerJsonReaderInterface
{
    public function read(string $absolutePath): array
    {
        if (!is_file($absolutePath)) {
            return [];
        }

        $raw = @file_get_contents($absolutePath);
        if ($raw === false) {
            return [];
        }

        $json = json_decode($raw, true);

        return is_array($json) ? $json : [];
    }
}
