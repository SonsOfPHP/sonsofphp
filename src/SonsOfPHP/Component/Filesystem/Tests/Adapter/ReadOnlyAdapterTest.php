<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\ReadOnlyAdapter;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

#[CoversClass(ReadOnlyAdapter::class)]
#[UsesClass(InMemoryAdapter::class)]
final class ReadOnlyAdapterTest extends TestCase
{
    private AdapterInterface|MockObject $adapter;

    public function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $adapter);
    }

    public function testItWillThrowExceptionWhenAddingFile(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->expectException(FilesystemException::class);
        $adapter->add('/path/to/file.ext', 'contents');
    }

    public function testItWillThrowExceptionWhenRemovingFile(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->expectException(FilesystemException::class);
        $adapter->remove('/path/to/file.ext');
    }

    public function testItWillThrowExceptionWhenCopyingFile(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->expectException(FilesystemException::class);
        $adapter->copy('/path/to/file.ext', '/path/to/dest.ext');
    }

    public function testItWillThrowExceptionWhenMovingFile(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->expectException(FilesystemException::class);
        $adapter->move('/path/to/file.ext', '/path/to/dest.ext');
    }

    public function testItWillGetFileContents(): void
    {
        $this->adapter->method('get')->willReturn('contents');

        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->assertSame('contents', $adapter->get('/path/to/file.ext'));
    }

    public function testItCanHas(): void
    {
        $this->adapter->method('has')->willReturn(true);

        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->assertTrue($adapter->has('/path/to/file.ext'));
    }

    public function testItCanIsFile(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);
        $this->assertFalse($adapter->isFile('/path/to/file.ext'));
    }

    public function testItCanCheckIfIsDirectory(): void
    {
        $adapter = new ReadOnlyAdapter(new InMemoryAdapter());
        $this->assertFalse($adapter->isDirectory('/path/to/file.ext'));
    }
}
