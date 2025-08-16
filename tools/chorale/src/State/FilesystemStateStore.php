<?php

declare(strict_types=1);

namespace Chorale\State;

/**
 * Reads/writes .chorale/state.json
 */
final class FilesystemStateStore implements StateStoreInterface
{
    private const REL_PATH = '/.chorale/state.json';

    public function read(string $projectRoot): array
    {
        $file = rtrim($projectRoot, '/') . self::REL_PATH;
        if (!is_file($file)) {
            return [];
        }

        $raw = @file_get_contents($file);
        if ($raw === false) {
            return [];
        }

        $json = json_decode($raw, true);
        return is_array($json) ? $json : [];
    }

    public function write(string $projectRoot, array $state): void
    {
        $dir = rtrim($projectRoot, '/') . '/.chorale';
        if (!is_dir($dir)) {
            @mkdir($dir, 0o777, true);
        }

        $file = $dir . '/state.json';
        @file_put_contents($file, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
