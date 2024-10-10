<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\NullAdapter;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;

/**
 * @internal
 */
#[CoversClass(NullAdapter::class)]
#[CoversNothing]
final class NullAdapterTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    #[DoesNotPerformAssertions]
    public function testItCanAddFile(): void
    {
        $adapter = new NullAdapter();
        $adapter->add('/path/to/file.txt', 'contents');
    }

    #[DoesNotPerformAssertions]
    public function testItCanRemoveFiles(): void
    {
        $adapter = new NullAdapter();
        $adapter->remove('/path/to/file.txt');
    }

    public function testItCanGetFileContents(): void
    {
        $adapter = new NullAdapter();
        $this->assertSame('', $adapter->get('/path/to/file.ext'));
    }

    public function testItHasNoFiles(): void
    {
        $adapter = new NullAdapter();
        $this->assertFalse($adapter->has('/path/to/file.ext'));
    }

    public function testItReturnsFalseForIsFile(): void
    {
        $adapter = new NullAdapter();
        $this->assertFalse($adapter->isFile('/path/to/file.ext'));
    }
}
