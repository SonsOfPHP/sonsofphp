<?php

declare(strict_types=1);

namespace Chorale\Discovery;

final class ComposerMetadata implements ComposerMetadataInterface
{
    public function read(string $dir): array
    {
        $file = rtrim($dir, '/') . '/composer.json';
        if (!is_file($file)) {
            return [];
        }

        $raw = @file_get_contents($file);
        if ($raw === false) {
            return [];
        }

        $json = json_decode($raw, true);
        if (!is_array($json)) {
            return [];
        }

        $name = isset($json['name']) && is_string($json['name']) ? $json['name'] : null;
        return $name !== null && $name !== '' && $name !== '0' ? ['name' => $name] : [];
    }
}
