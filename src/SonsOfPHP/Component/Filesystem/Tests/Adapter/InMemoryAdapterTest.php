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

        $adapter->write('/test.txt', $contents);
        $this->assertSame($contents, $adapter->read('/test.txt'));
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
}
