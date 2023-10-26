<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;
use SonsOfPHP\Component\Filesystem\FilesystemInterface;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Filesystem;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Filesystem
 *
 * @internal
 */
final class FilesystemTest extends TestCase
{
    private InMemoryAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new InMemoryAdapter();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertInstanceOf(FilesystemInterface::class, $filesystem);
    }

    /**
     * @covers ::write
     */
    public function testItCanWrite(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertSame('contents', $filesystem->read('/path/to/file.ext'));
    }

    /**
     * @covers ::read
     */
    public function testItCanRead(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertSame('contents', $filesystem->read('/path/to/file.ext'));
    }

    /**
     * @covers ::exists
     */
    public function testItCanCheckIfExists(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
    }

    /**
     * @covers ::delete
     */
    public function testItCanDeleteFile(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
        $filesystem->write('/path/to/file.ext', 'contents');
        $this->assertTrue($filesystem->exists('/path/to/file.ext'));
        $filesystem->delete('/path/to/file.ext');
        $this->assertFalse($filesystem->exists('/path/to/file.ext'));
    }

    /**
     * @covers ::copy
     */
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

    /**
     * @covers ::move
     */
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
