<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\NullAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\NullAdapter
 *
 * @internal
 */
final class NullAdapterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @covers ::add
     * @doesNotPerformAssertions
     */
    public function testItCanAddFile(): void
    {
        $adapter = new NullAdapter();
        $adapter->add('/path/to/file.txt', 'contents');
    }

    /**
     * @covers ::remove
     * @doesNotPerformAssertions
     */
    public function testItCanRemoveFiles(): void
    {
        $adapter = new NullAdapter();
        $adapter->remove('/path/to/file.txt');
    }

    /**
     * @covers ::get
     */
    public function testItCanGetFileContents(): void
    {
        $adapter = new NullAdapter();
        $this->assertSame('', $adapter->get('/path/to/file.ext'));
    }

    /**
     * @covers ::has
     */
    public function testItHasNoFiles(): void
    {
        $adapter = new NullAdapter();
        $this->assertFalse($adapter->has('/path/to/file.ext'));
    }

    /**
     * @covers ::isFile
     */
    public function testItReturnsFalseForIsFile(): void
    {
        $adapter = new NullAdapter();
        $this->assertFalse($adapter->isFile('/path/to/file.ext'));
    }
}
