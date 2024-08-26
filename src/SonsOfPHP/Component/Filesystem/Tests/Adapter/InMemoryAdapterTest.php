<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;

#[CoversClass(InMemoryAdapter::class)]
final class InMemoryAdapterTest extends TestCase
{
    private AdapterInterface $adapter;

    protected function setUp(): void
    {
        $this->adapter = new InMemoryAdapter();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->adapter);
    }

    public function testItCanWriteAFileAndReadItLater(): void
    {
        $contents = 'Pretend this is the contents of a file';

        $this->adapter->add('/path/to/test.txt', $contents);
        $this->assertSame($contents, $this->adapter->get('/path/to/test.txt'));
    }

    public function testItWillThrowExceptionWhenFileNotFound(): void
    {
        $this->expectException(UnableToReadFileException::class);
        $this->adapter->get('test.txt');
    }

    public function testItCanDeleteFile(): void
    {
        $this->adapter->add('/path/to/test.txt', 'testing');
        $this->assertSame('testing', $this->adapter->get('/path/to/test.txt'));

        $this->adapter->remove('/path/to/test.txt');
        $this->expectException(UnableToReadFileException::class);
        $this->adapter->get('/path/to/test.txt');
    }

    public function testItCanCopyFile(): void
    {
        $this->adapter->add('test.txt', 'testing');
        $this->adapter->copy('test.txt', 'test2.txt');
        $this->assertSame('testing', $this->adapter->get('test.txt'));
        $this->assertSame('testing', $this->adapter->get('test2.txt'));
    }

    public function testItCanMoveFile(): void
    {
        $this->adapter->add('test.txt', 'testing');
        $this->adapter->move('test.txt', 'test2.txt');
        $this->assertSame('testing', $this->adapter->get('test2.txt'));
        $this->expectException(UnableToReadFileException::class);
        $this->adapter->get('test.txt');
    }

    public function testItCanSupportStream(): void
    {
        $stream = fopen('php://memory', 'w+');
        fwrite($stream, 'Just a test');
        $this->adapter->add('test.txt', $stream);
        fclose($stream);

        $this->assertSame('Just a test', $this->adapter->get('test.txt'));
    }

    public function testItCanCheckIfFilesExist(): void
    {
        $this->assertFalse($this->adapter->has('test.txt'));
        $this->adapter->add('test.txt', 'testing');
        $this->assertTrue($this->adapter->has('test.txt'));
    }

    public function testItCanCheckIfIsFile(): void
    {
        $this->adapter->add('/path/to/file.txt', 'testing');

        $this->assertTrue($this->adapter->isFile('/path/to/file.txt'));
    }

    public function testItCanCheckIfIsDirectory(): void
    {
        $this->adapter->add('/path/to/file.txt', 'testing');

        $this->assertTrue($this->adapter->isDirectory('/path/to'));
    }

    public function testItWillDeleteAllFilesInDirectory(): void
    {
        $this->adapter->add('/path/to/one.txt', 'testing');
        $this->adapter->add('/path/to/two.txt', 'testing');
        $this->adapter->remove('/path/to');
        $this->assertFalse($this->adapter->isFile('/path/to/one.ext'));
        $this->assertFalse($this->adapter->isFile('/path/to/two.ext'));
    }

    public function testItWillRemoveDirectroy(): void
    {
        $this->adapter->add('/path/to/one.txt', 'testing');
        $this->adapter->add('/path/to/two.txt', 'testing');
        $this->adapter->removeDirectory('/path/to');
        $this->assertFalse($this->adapter->isFile('/path/to/one.ext'));
        $this->assertFalse($this->adapter->isFile('/path/to/two.ext'));
    }
}
