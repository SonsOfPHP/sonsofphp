<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(NativeAdapter::class)]
final class NativeAdapterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NativeAdapter('/tmp');

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $adapter);
    }
}
