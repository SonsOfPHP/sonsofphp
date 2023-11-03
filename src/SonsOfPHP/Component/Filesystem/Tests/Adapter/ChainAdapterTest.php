<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter
 *
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter
 */
final class ChainAdapterTest extends TestCase
{
    private array $adapters = [];

    public function setUp(): void
    {
        $this->adapters[] = new InMemoryAdapter();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $adapter);
    }

    /**
     * @covers ::add
     */
    public function testItCanAdd(): void
    {
        $adp = $this->createMock(AdapterInterface::class);
        $adp->expects($this->once())->method('add');
        $this->adapters[] = $adp;

        $adapter = new ChainAdapter($this->adapters);
        $adapter->add('/path/to/file.txt', 'testing');
    }

    /**
     * @covers ::get
     */
    public function testItCanGetFile(): void
    {
        $adp = $this->createMock(AdapterInterface::class);
        $adp->method('has')->willReturn(true);
        $adp->expects($this->once())->method('get');
        $this->adapters[] = $adp;

        $adapter = new ChainAdapter($this->adapters);
        $adapter->get('/path/to/file.txt');
    }

    /**
     * @covers ::get
     */
    public function testItWillThrowExceptionWhenFileNotFound(): void
    {
        $adp = $this->createMock(AdapterInterface::class);
        $adp->method('has')->willReturn(false);
        $this->adapters[] = $adp;

        $adapter = new ChainAdapter($this->adapters);
        $this->expectException(FileNotFoundException::class);
        $adapter->get('/path/to/file.txt');
    }

    /**
     * @covers ::remove
     */
    public function testItCanRemoveFiles(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->has('/path/to/file.txt'));
        $adapter->add('/path/to/file.txt', 'testing');
        $this->assertTrue($adapter->has('/path/to/file.txt'));
        $adapter->remove('/path/to/file.txt');
        $this->assertFalse($adapter->has('/path/to/file.txt'));
    }

    /**
     * @covers ::has
     */
    public function testItCanHas(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->has('/path/to/file.txt'));
        $adapter->add('/path/to/file.txt', 'testing');
        $this->assertTrue($adapter->has('/path/to/file.txt'));
    }

    /**
     * @covers ::isFile
     */
    public function testItCanIsFile(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->isFile('/path/to/file.txt'));
        $adapter->add('/path/to/file.txt', 'testing');
        $this->assertFalse($adapter->isFile('/path/to'));
        $this->assertTrue($adapter->isFile('/path/to/file.txt'));
    }

    /**
     * @covers ::copy
     */
    public function testItCanCopyFile(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->has('/path/to/source.txt'));
        $this->assertFalse($adapter->has('/path/to/destination.txt'));

        $adapter->add('/path/to/source.txt', 'testing');
        $this->assertTrue($adapter->has('/path/to/source.txt'));
        $this->assertFalse($adapter->has('/path/to/destination.txt'));

        $adapter->copy('/path/to/source.txt', '/path/to/destination.txt');
        $this->assertTrue($adapter->has('/path/to/source.txt'));
        $this->assertTrue($adapter->has('/path/to/destination.txt'));
    }

    /**
     * @covers ::isDirectory
     */
    public function testItCanCheckIfIsDirectoryExists(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->isDirectory('/path/to'));
        $adapter->add('/path/to/file.txt', 'testing');
        $this->assertTrue($adapter->isDirectory('/path/to'));
    }

    /**
     * @covers ::move
     */
    public function testItCanMoveFiles(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $this->assertFalse($adapter->has('/path/to/source.txt'));
        $this->assertFalse($adapter->has('/path/to/destination.txt'));

        $adapter->add('/path/to/source.txt', 'testing');
        $this->assertTrue($adapter->has('/path/to/source.txt'));
        $this->assertFalse($adapter->has('/path/to/destination.txt'));

        $adapter->move('/path/to/source.txt', '/path/to/destination.txt');
        $this->assertFalse($adapter->has('/path/to/source.txt'));
        $this->assertTrue($adapter->has('/path/to/destination.txt'));
    }
}
