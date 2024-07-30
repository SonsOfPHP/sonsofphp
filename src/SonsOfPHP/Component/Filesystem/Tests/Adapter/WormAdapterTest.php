<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\WormAdapter;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 *
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\WormAdapter
 * @coversNothing
 */
#[CoversClass(WormAdapter::class)]
final class WormAdapterTest extends TestCase
{
    private AdapterInterface $adapter;

    public function setUp(): void
    {
        $this->adapter = new InMemoryAdapter();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $adapter);
    }

    public function testItCanAddFileOnlyOnce(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $adapter->add('/path/to/file.ext', 'contents');
        $this->expectException(FilesystemException::class);
        $adapter->add('/path/to/file.ext', 'contents');
    }

    public function testItCannotRemoveFile(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->expectException(FilesystemException::class);
        $adapter->remove('/path/to/file.ext');
    }

    public function testItCannotMoveFile(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->expectException(FilesystemException::class);
        $adapter->move('/path/to/file.ext', '/path/to/dest.ext');
    }

    public function testItCanGetFileContents(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $adapter->add('/path/to/file.ext', 'contents');
        $this->assertSame('contents', $adapter->get('/path/to/file.ext'));
    }

    public function testItCanHas(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->assertFalse($adapter->has('/path/to/file.ext'));
        $adapter->add('/path/to/file.ext', 'contents');
        $this->assertTrue($adapter->has('/path/to/file.ext'));
    }

    public function testItCanCheckIfIsFile(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->assertFalse($adapter->isFile('/path/to/file.ext'));
        $adapter->add('/path/to/file.ext', 'contents');
        $this->assertTrue($adapter->isFile('/path/to/file.ext'));
    }

    public function testItCanCheckIfIsDirectory(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->assertFalse($adapter->isDirectory('/path/to/file.ext'));
        $adapter->add('/path/to/file.ext', 'contents');
        $this->assertTrue($adapter->isDirectory('/path/to'));
    }

    public function testItCanCopyFileOnlyIfDestinationExists(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $adapter->add('/path/to/source.ext', 'contents');
        $adapter->copy('/path/to/source.ext', '/path/to/destination.txt');

        $adapter->add('/path/to/another.ext', 'contents');
        $this->expectException(FilesystemException::class);
        $adapter->copy('/path/to/source.ext', '/path/to/another.ext');
    }
}
