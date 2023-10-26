<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\WormAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\WormAdapter
 *
 * @internal
 */
final class WormAdapterTest extends TestCase
{
    private AdapterInterface $adapter;

    public function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new WormAdapter($this->adapter);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
