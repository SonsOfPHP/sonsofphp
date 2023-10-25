<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter
 *
 * @internal
 */
final class InMemoryAdapterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new InMemoryAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @covers ::write
     * @covers ::read
     */
    public function testItCanWriteAFileAndReadItLater(): void
    {
        $adapter = new InMemoryAdapter();

        $contents = 'Pretend this is the contents of a file';

        $adapter->write('/path/to/test.txt', $contents);
        $this->assertSame($contents, $adapter->read('/path/to/test.txt'));
    }

    /**
     * @covers ::read
     */
    public function testItWillThrowExceptionWhenFileNotFound(): void
    {
        $adapter = new InMemoryAdapter();
        $this->expectException(UnableToReadFileException::class);
        $adapter->read('test.txt');
    }

    /**
     * @covers ::delete
     */
    public function testItCanDeleteFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->write('/path/to/test.txt', 'testing');
        $this->assertSame('testing', $adapter->read('/path/to/test.txt'));

        $adapter->delete('/path/to/test.txt');
        $this->expectException(UnableToReadFileException::class);
        $adapter->read('/path/to/test.txt');
    }

    /**
     * @covers ::copy
     */
    public function testItCanCopyFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->write('test.txt', 'testing');
        $adapter->copy('test.txt', 'test2.txt');
        $this->assertSame('testing', $adapter->read('test.txt'));
        $this->assertSame('testing', $adapter->read('test2.txt'));
    }

    /**
     * @covers ::move
     */
    public function testItCanMoveFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->write('test.txt', 'testing');
        $adapter->move('test.txt', 'test2.txt');
        $this->assertSame('testing', $adapter->read('test2.txt'));
        $this->expectException(UnableToReadFileException::class);
        $adapter->read('test.txt');
    }

    /**
     * @covers ::read
     */
    public function testItCanSupportStream(): void
    {
        $adapter = new InMemoryAdapter();
        $stream = fopen('php://memory', 'w+');
        fwrite($stream, 'Just a test');
        $adapter->write('test.txt', $stream);
        fclose($stream);

        $this->assertSame('Just a test', $adapter->read('test.txt'));
    }

    /**
     * @covers ::exists
     */
    public function testItCanCheckIfFilesExist(): void
    {
        $adapter = new InMemoryAdapter();
        $this->assertFalse($adapter->exists('test.txt'));
        $adapter->write('test.txt', 'testing');
        $this->assertTrue($adapter->exists('test.txt'));
    }

    /**
     * @covers ::isFile
     */
    public function testItCanCheckIfIsFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->write('/path/to/file.txt', 'testing');

        $this->assertTrue($adapter->isFile('/path/to/file.txt'));
    }

    /**
     * @covers ::isFile
     */
    public function testItCanCheckIfIsDirectory(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->write('/path/to/file.txt', 'testing');

        $this->assertTrue($adapter->isDirectory('/path/to'));
    }
}
