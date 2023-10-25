<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject;
use SonsOfPHP\Component\Filesystem\FilesystemInterface;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Filesystem;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Filesystem
 *
 * @internal
 */
final class FilesystemTest extends TestCase
{
    private AdapterInterface|MockObject $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->assertInstanceOf(FilesystemInterface::class, $filesystem);
    }
}
