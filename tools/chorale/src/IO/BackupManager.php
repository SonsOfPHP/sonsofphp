<?php

declare(strict_types=1);

namespace Chorale\IO;

final class BackupManager implements BackupManagerInterface
{
    public function backup(string $filePath): string
    {
        $dir = dirname($filePath);
        $backupDir = $dir . '/.chorale/backup';
        if (!is_dir($backupDir) && (!@mkdir($backupDir, 0o775, true) && !is_dir($backupDir))) {
            throw new \RuntimeException('Failed to create backup directory: ' . $backupDir);
        }

        $ts = (new \DateTimeImmutable('now'))->format('Ymd-His');
        $base = basename($filePath);
        $dest = $backupDir . '/' . $base . '.' . $ts . '.bak';

        if (is_file($filePath)) {
            if (@copy($filePath, $dest) === false) {
                throw new \RuntimeException('Failed to create backup file: ' . $dest);
            }
        } elseif (@file_put_contents($dest, '') === false) {
            // Create an empty marker so rollback tooling has a reference
            throw new \RuntimeException('Failed to create backup placeholder: ' . $dest);
        }

        return $dest;
    }

    public function restore(string $backupFilePath, string $targetPath): void
    {
        if (!is_file($backupFilePath)) {
            throw new \RuntimeException('Backup file not found: ' . $backupFilePath);
        }

        if (@copy($backupFilePath, $targetPath) === false) {
            throw new \RuntimeException('Failed to restore backup to: ' . $targetPath);
        }
    }
}
