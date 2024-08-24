<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FileNotFoundExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\UnableToCopyFileExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\UnableToDeleteFileExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\UnableToMoveFileExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\UnableToReadFileExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\UnableToWriteFileExceptionInterface;

#[CoversClass(NativeAdapter::class)]
final class NativeAdapterTest extends TestCase
{
    private AdapterInterface $adapter;
    private string $prefix;

    protected function setUp(): void
    {
        $this->prefix  = sys_get_temp_dir() . '/' . uniqid();
        $this->adapter = new NativeAdapter($this->prefix);
    }

    protected function tearDown(): void
    {
        // @todo Clean up all the files
        //rmdir($this->prefix);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NativeAdapter('/tmp');

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $adapter);
    }

    public function testItWillWriteFile(): void
    {
        $path = __FUNCTION__;
        $this->assertFalse(is_file($this->prefix . '/' . $path));
        $this->adapter->add($path, 'test');
        $this->assertTrue(is_file($this->prefix . '/' . $path));
    }

    public function testItWillThrowExceptionWhenAddingFileThatExists(): void
    {
        $path = __FUNCTION__;
        $this->assertFalse(is_file($this->prefix . '/' . $path));
        $this->adapter->add($path, 'test');
        $this->assertTrue(is_file($this->prefix . '/' . $path));

        $this->expectException(FilesystemExceptionInterface::class);
        $this->adapter->add($path, 'test');
    }

    public function testItCanReturnTheContentsOfAFile(): void
    {
        $path = __FUNCTION__;
        $this->adapter->add($path, 'test');

        $this->assertSame('test', $this->adapter->get($path));
    }

    public function testItCanDeleteFileThatExists(): void
    {
        $path = __FUNCTION__;
        $this->adapter->add($path, 'test');
        $this->assertTrue(is_file($this->prefix . '/' . $path));

        $this->adapter->remove($path);
        $this->assertFalse(is_file($this->prefix . '/' . $path));
    }

    public function testItWillReturnCorrectlyForHasWithFile(): void
    {
        $path = __FUNCTION__;
        $this->assertFalse($this->adapter->has($path));
        $this->adapter->add($path, 'test');
        $this->assertTrue($this->adapter->has($path));
    }

    public function testItWillReturnCorrectlyForHasWithDirectory(): void
    {
        $path = __FUNCTION__;
        $this->assertFalse($this->adapter->has($path));
        $this->adapter->makeDirectory($path);
        $this->assertTrue($this->adapter->has($path));
    }

    public function testItCanTellTheDifferenceBetweenAFileAndDirectory(): void
    {
        $dirPath = 'test.d';
        $filePath = 'test';
        $this->adapter->makeDirectory($dirPath);
        $this->adapter->add($filePath, 'test');

        $this->assertFalse($this->adapter->isFile($dirPath));
        $this->assertTrue($this->adapter->isFile($filePath));

        $this->assertTrue($this->adapter->isDirectory($dirPath));
        $this->assertFalse($this->adapter->isDirectory($filePath));
    }

    public function testItCanCopyAFile(): void
    {
        $source = 'source.txt';
        $destination = 'destination.txt';
        $this->adapter->add($source, 'test');
        $this->adapter->copy($source, $destination);
        $this->assertTrue(is_file($this->prefix . '/' . $source));
        $this->assertTrue(is_file($this->prefix . '/' . $destination));
        $this->assertSame('test', $this->adapter->get($destination));
    }

    public function testItCanMoveAFile(): void
    {
        $source = 'source.txt';
        $destination = 'destination.txt';
        $this->adapter->add($source, 'test');
        $this->adapter->move($source, $destination);
        $this->assertFalse(is_file($this->prefix . '/' . $source));
        $this->assertTrue(is_file($this->prefix . '/' . $destination));
        $this->assertSame('test', $this->adapter->get($destination));
    }

    public function testItWillThrowExceptionWhenFileNotFound(): void
    {
        $path = __FUNCTION__;
        $this->expectException(FileNotFoundExceptionInterface::class);
        $this->adapter->get($path);
    }

    public function testItWillThrowExceptionWhenDeletingAFileThatDoesNotExist(): void
    {
        $path = __FUNCTION__;
        $this->expectException(FileNotFoundExceptionInterface::class);
        $this->adapter->remove($path);
    }
}
