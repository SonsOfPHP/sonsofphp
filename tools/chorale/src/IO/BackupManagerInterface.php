<?php

declare(strict_types=1);

namespace Chorale\IO;

interface BackupManagerInterface
{
    /**
     * Create a timestamped backup of the given file into .chorale/backup/.
     * Returns backup file path on success.
     */
    public function backup(string $filePath): string;

    /**
     * Restore from a given backup file path to the target path, replacing existing file.
     */
    public function restore(string $backupFilePath, string $targetPath): void;
}
