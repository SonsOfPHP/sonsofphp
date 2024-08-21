<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;

/**
 * @uses \SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter
 * @coversNothing
 */
#[CoversClass(InMemoryAdapter::class)]
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

    public function testItCanWriteAFileAndReadItLater(): void
    {
        $adapter = new InMemoryAdapter();

        $contents = 'Pretend this is the contents of a file';

        $adapter->add('/path/to/test.txt', $contents);
        $this->assertSame($contents, $adapter->get('/path/to/test.txt'));
    }

    public function testItWillThrowExceptionWhenFileNotFound(): void
    {
        $adapter = new InMemoryAdapter();
        $this->expectException(UnableToReadFileException::class);
        $adapter->get('test.txt');
    }

    public function testItCanDeleteFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->add('/path/to/test.txt', 'testing');
        $this->assertSame('testing', $adapter->get('/path/to/test.txt'));

        $adapter->remove('/path/to/test.txt');
        $this->expectException(UnableToReadFileException::class);
        $adapter->get('/path/to/test.txt');
    }

    public function testItCanCopyFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->add('test.txt', 'testing');
        $adapter->copy('test.txt', 'test2.txt');
        $this->assertSame('testing', $adapter->get('test.txt'));
        $this->assertSame('testing', $adapter->get('test2.txt'));
    }

    public function testItCanMoveFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->add('test.txt', 'testing');
        $adapter->move('test.txt', 'test2.txt');
        $this->assertSame('testing', $adapter->get('test2.txt'));
        $this->expectException(UnableToReadFileException::class);
        $adapter->get('test.txt');
    }

    public function testItCanSupportStream(): void
    {
        $adapter = new InMemoryAdapter();
        $stream = fopen('php://memory', 'w+');
        fwrite($stream, 'Just a test');
        $adapter->add('test.txt', $stream);
        fclose($stream);

        $this->assertSame('Just a test', $adapter->get('test.txt'));
    }

    public function testItCanCheckIfFilesExist(): void
    {
        $adapter = new InMemoryAdapter();
        $this->assertFalse($adapter->has('test.txt'));
        $adapter->add('test.txt', 'testing');
        $this->assertTrue($adapter->has('test.txt'));
    }

    public function testItCanCheckIfIsFile(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->add('/path/to/file.txt', 'testing');

        $this->assertTrue($adapter->isFile('/path/to/file.txt'));
    }

    public function testItCanCheckIfIsDirectory(): void
    {
        $adapter = new InMemoryAdapter();
        $adapter->add('/path/to/file.txt', 'testing');

        $this->assertTrue($adapter->isDirectory('/path/to'));
    }
}
