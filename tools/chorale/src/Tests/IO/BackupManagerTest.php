<?php

declare(strict_types=1);

namespace Chorale\Tests\IO;

use Chorale\IO\BackupManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(BackupManager::class)]
#[Group('unit')]
#[Small]
final class BackupManagerTest extends TestCase
{
    public function testBackupCreatesPlaceholderWhenFileMissing(): void
    {
        $bm = new BackupManager();
        $dir = sys_get_temp_dir() . '/chorale_' . uniqid();
        @mkdir($dir);
        $dest = $bm->backup($dir . '/file.yaml');
        $this->assertFileExists($dest);
    }

    #[Test]
    public function testRestoreCopiesBackupToTarget(): void
    {
        $bm = new BackupManager();
        $dir = sys_get_temp_dir() . '/chorale_' . uniqid();
        @mkdir($dir);
        $srcFile = $dir . '/file.yaml';
        file_put_contents($srcFile, 'abc');
        $backup = $bm->backup($srcFile);
        $target = $dir . '/restored.yaml';
        $bm->restore($backup, $target);
        $this->assertFileExists($target);
    }
}
