<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Pager\Adapter\CallableAdapter
 *
 * @uses \SonsOfPHP\Component\Pager\Adapter\CallableAdapter
 */
final class CallableAdapterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new CallableAdapter(
            count: fn() => 0,
            slice: fn() => [],
        );

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @covers ::count
     */
    public function testCount(): void
    {
        $adapter = new CallableAdapter(
            count: fn() => 100,
            slice: fn() => [],
        );

        $this->assertCount(100, $adapter);
    }

    /**
     * @covers ::getSlice
     */
    public function testGetSlice(): void
    {
        $adapter = new CallableAdapter(
            count: fn() => 100,
            slice: fn() => ['unit.test'],
        );

        $this->assertCount(1, $adapter->getSlice(0, null));
    }
}
