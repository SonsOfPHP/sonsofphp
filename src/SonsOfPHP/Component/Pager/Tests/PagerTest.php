<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;
use SonsOfPHP\Component\Pager\Pager;
use SonsOfPHP\Contract\Pager\PagerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Pager\Pager
 *
 * @uses \SonsOfPHP\Component\Pager\Pager
 * @uses \SonsOfPHP\Component\Pager\Adapter\ArrayAdapter
 * @uses \SonsOfPHP\Component\Pager\Adapter\CallableAdapter
 */
final class PagerTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertInstanceOf(PagerInterface::class, $pager);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWillSetCorrectOptions(): void
    {
        $pager = new Pager(new ArrayAdapter([]), [
            'current_page' => 2,
            'max_per_page' => 25,
        ]);

        $this->assertSame(2, $pager->getCurrentPage());
        $this->assertSame(25, $pager->getMaxPerPage());
    }

    /**
     * @covers ::getCurrentPageResults
     */
    public function testGetCurrentPageResults(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertCount(0, $pager->getCurrentPageResults());
    }

    /**
     * @covers ::getTotalResults
     */
    public function testGetTotalResults(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(0, $pager->getTotalResults());
    }

    /**
     * @covers ::getTotalPages
     */
    public function testGetTotalPagesWhenThereAreResults(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));

        $this->assertSame(10, $pager->getTotalPages());
    }

    /**
     * @covers ::getTotalPages
     */
    public function testGetTotalPages(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(1, $pager->getTotalPages());
    }

    /**
     * @covers ::haveToPaginate
     */
    public function testHaveToPaginateWillReturnFalseWhenMaxPerPageIsNull(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setMaxPerPage(null);

        $this->assertFalse($pager->haveToPaginate());
    }

    /**
     * @covers ::haveToPaginate
     */
    public function testHaveToPaginate(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->haveToPaginate());
    }

    /**
     * @covers ::hasPreviousPage
     */
    public function testHasPreviousPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->hasPreviousPage());
    }

    /**
     * @covers ::getPreviousPage
     */
    public function testGetPreviousPageWhenThereAreMultiplePages(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));
        $pager->setCurrentPage(2);

        $this->assertSame(1, $pager->getPreviousPage());
    }

    /**
     * @covers ::getPreviousPage
     */
    public function testGetPreviousPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertNull($pager->getPreviousPage());
    }

    /**
     * @covers ::hasNextPage
     */
    public function testHasNextPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->hasNextPage());
    }

    /**
     * @covers ::getNextPage
     */
    public function testGetNextPageWhenThereAreMultiplePages(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));

        $this->assertSame(2, $pager->getNextPage());
    }

    /**
     * @covers ::getNextPage
     */
    public function testGetNextPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertNull($pager->getNextPage());
    }

    /**
     * @covers ::getCurrentPage
     */
    public function testGetCurrentPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(1, $pager->getCurrentPage());
    }

    /**
     * @covers ::setCurrentPage
     */
    public function testSetCurrentPageWithInvalidArgument(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->expectException('InvalidArgumentException');
        $pager->setCurrentPage(0);
    }

    /**
     * @covers ::setCurrentPage
     */
    public function testSetCurrentPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setCurrentPage(2);

        $this->assertSame(2, $pager->getCurrentPage());
    }

    /**
     * @covers ::getMaxPerPage
     */
    public function testGetMaxPerPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(10, $pager->getMaxPerPage());
    }

    /**
     * @covers ::setMaxPerPage
     */
    public function testSetMaxPerPageWillTakeNull(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $pager->setMaxPerPage(null);
        $this->assertNull($pager->getMaxPerPage());
    }

    /**
     * @covers ::setMaxPerPage
     */
    public function testSetMaxPerPageWithInvalidArgument(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->expectException('InvalidArgumentException');
        $pager->setMaxPerPage(0);
    }

    /**
     * @covers ::setMaxPerPage
     */
    public function testSetMaxPerPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setMaxPerPage(25);

        $this->assertSame(25, $pager->getMaxPerPage());
    }

    /**
     * @covers ::count
     */
    public function testCount(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertCount(0, $pager);
        $this->assertSame(0, $pager->count());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIteratorWhenGenerator(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn() => 0,
            slice: function () {
                yield new \stdClass();
                yield new \stdClass();
            },
        ));

        $this->assertInstanceOf('Generator', $pager->getIterator());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIteratorWhenIteratorAggregateIsReturned(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn() => 1,
            slice: function () {
                return new class () implements \IteratorAggregate {
                    public function getIterator(): \Traversable
                    {
                        return new \ArrayIterator([]);
                    }
                };
            },
        ));

        $this->assertInstanceOf('ArrayIterator', $pager->getIterator());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIteratorWhenIteratorIsReturned(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn() => 1,
            slice: fn() => new \ArrayIterator([]),
        ));

        $this->assertInstanceOf('ArrayIterator', $pager->getIterator());
    }

    /**
     * @covers ::getIterator
     */
    public function testGetIterator(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertInstanceOf('Traversable', $pager->getIterator());
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testJsonSerializeWhenResultsAreTraversable(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn() => 2,
            slice: function () {
                yield new \stdClass();
                yield new \stdClass();
            },
        ));

        $this->assertSame('[{},{}]', json_encode($pager));
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame('[]', json_encode($pager));
    }
}
