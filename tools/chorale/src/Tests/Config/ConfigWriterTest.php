<?php

declare(strict_types=1);

namespace Chorale\Tests\Config;

use Chorale\Config\ConfigWriter;
use Chorale\IO\BackupManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigWriter::class)]
#[Group('unit')]
#[Small]
final class ConfigWriterTest extends TestCase
{
    /** @var BackupManagerInterface&MockObject */
    private BackupManagerInterface $backup;

    protected function setUp(): void
    {
        $this->backup = $this->createMock(BackupManagerInterface::class);
    }

    #[Test]
    public function testWriteCreatesYamlFile(): void
    {
        $dir = sys_get_temp_dir() . '/chorale_' . uniqid();
        @mkdir($dir);
        $this->backup->expects(self::once())->method('backup')->with($dir . '/conf.yaml')->willReturn($dir . '/.chorale/backup/conf.yaml.bak');
        $w = new ConfigWriter($this->backup, 'conf.yaml');
        $w->write($dir, ['version' => 1]);
        self::assertFileExists($dir . '/conf.yaml');
    }

    #[Test]
    public function testWriteThrowsWhenTempFileCannotBeWritten(): void
    {
        $this->backup->expects(self::once())->method('backup')->with($this->anything())->willReturn('/tmp/x');
        $w = new ConfigWriter($this->backup, 'conf.yaml');
        $this->expectException(\RuntimeException::class);
        $w->write(sys_get_temp_dir() . uniqid(), ['a' => 'b']);
    }
}
