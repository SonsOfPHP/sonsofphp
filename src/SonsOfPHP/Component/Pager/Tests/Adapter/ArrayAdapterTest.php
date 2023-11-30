<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Pager\Adapter\ArrayAdapter
 *
 * @uses \SonsOfPHP\Component\Pager\Adapter\ArrayAdapter
 */
final class ArrayAdapterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ArrayAdapter([]);
        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @covers ::count
     */
    public function testCount(): void
    {
        $adapter = new ArrayAdapter([]);

        $this->assertCount(0, $adapter);
    }

    /**
     * @covers ::getSlice
     */
    public function testGetSlice(): void
    {
        $adapter = new ArrayAdapter([
            'one',
            'two',
            'three',
        ]);

        $this->assertSame('one', $adapter->getSlice(0, 1)[0]);
        $this->assertSame('two', $adapter->getSlice(1, 1)[0]);
        $this->assertSame('three', $adapter->getSlice(2, 1)[0]);
        $this->assertCount(3, $adapter->getSlice(0, null));
    }
}
