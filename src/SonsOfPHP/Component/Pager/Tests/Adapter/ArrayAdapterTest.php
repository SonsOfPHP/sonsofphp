<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

#[CoversClass(ArrayAdapter::class)]
#[UsesClass(ArrayAdapter::class)]
final class ArrayAdapterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ArrayAdapter([]);
        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testCount(): void
    {
        $adapter = new ArrayAdapter([]);

        $this->assertEmpty($adapter);
    }

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
