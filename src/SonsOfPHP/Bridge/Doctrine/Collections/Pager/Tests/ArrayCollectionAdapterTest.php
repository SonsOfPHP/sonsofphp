<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\Collections\Pager\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\Collections\Pager\ArrayCollectionAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @uses \SonsOfPHP\Bridge\Doctrine\Collections\Pager\ArrayCollectionAdapter
 * @coversNothing
 */
#[CoversClass(ArrayCollectionAdapter::class)]
final class ArrayCollectionAdapterTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $adapter = new ArrayCollectionAdapter(new ArrayCollection());

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testCount(): void
    {
        $adapter = new ArrayCollectionAdapter(new ArrayCollection(range(0, 9)));

        $this->assertCount(10, $adapter);
    }

    public function testGetSlice(): void
    {
        $adapter = new ArrayCollectionAdapter(new ArrayCollection(range(0, 9)));

        $this->assertSame([0], $adapter->getSlice(0, 1));
    }
}
