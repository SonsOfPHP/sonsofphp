<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @uses \SonsOfPHP\Component\Pager\Adapter\CallableAdapter
 * @coversNothing
 */
#[CoversClass(CallableAdapter::class)]
final class CallableAdapterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new CallableAdapter(
            count: fn(): int => 0,
            slice: fn(): array => [],
        );

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testCount(): void
    {
        $adapter = new CallableAdapter(
            count: fn(): int => 100,
            slice: fn(): array => [],
        );

        $this->assertCount(100, $adapter);
    }

    public function testGetSlice(): void
    {
        $adapter = new CallableAdapter(
            count: fn(): int => 100,
            slice: fn(): array => ['unit.test'],
        );

        $this->assertCount(1, $adapter->getSlice(0, null));
    }
}
