<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Filesystem;
use SonsOfPHP\Component\Filesystem\FilesystemInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter
 * @uses \SonsOfPHP\Component\Filesystem\Filesystem
 * @coversNothing
 */
#[CoversClass(Filesystem::class)]
final class FilesystemTest extends TestCase
{
    private InMemoryAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new InMemoryAdapter();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertInstanceOf(FilesystemInterface::class, $filesystem);
    }

    public function testItCanWrite(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertSame('contents', $filesystem->read('/path/to/file.ext'));
    }

    public function testItCanRead(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertSame('contents', $filesystem->read('/path/to/file.ext'));
    }

    public function testItCanCheckIfExists(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
    }

    public function testItCanDeleteFile(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
        $filesystem->delete('/path/to/file.ext');
        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
    }

    public function testItCanCopyFiles(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
        $this->assertFalse($filesystem->exists('/path/to/destination.ext'));

        $filesystem->copy('/path/to/file.ext', '/path/to/destination.ext');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
        $this->assertTrue($filesystem->exists('/path/to/destination.ext'));
    }

    public function testItCanMoveFiles(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
        $this->assertFalse($filesystem->exists('/path/to/destination.ext'));

        $filesystem->move('/path/to/file.ext', '/path/to/destination.ext');
        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
        $this->assertTrue($filesystem->exists('/path/to/destination.ext'));
    }
}
