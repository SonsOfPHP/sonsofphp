<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Tests;

use ArrayIterator;
use Generator;
use IteratorAggregate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;
use SonsOfPHP\Component\Pager\Pager;
use SonsOfPHP\Contract\Pager\PagerInterface;
use stdClass;
use Traversable;

#[CoversClass(Pager::class)]
#[UsesClass(ArrayAdapter::class)]
#[UsesClass(CallableAdapter::class)]
final class PagerTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertInstanceOf(PagerInterface::class, $pager);
    }

    public function testConstructWillSetCorrectOptions(): void
    {
        $pager = new Pager(new ArrayAdapter([]), [
            'current_page' => 2,
            'max_per_page' => 25,
        ]);

        $this->assertSame(2, $pager->getCurrentPage());
        $this->assertSame(25, $pager->getMaxPerPage());
    }

    public function testGetCurrentPageResults(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertEmpty($pager->getCurrentPageResults());
    }

    public function testGetTotalResults(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(0, $pager->getTotalResults());
    }

    public function testGetTotalPagesWhenThereAreResults(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));

        $this->assertSame(10, $pager->getTotalPages());
    }

    public function testGetTotalPages(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(1, $pager->getTotalPages());
    }

    public function testHaveToPaginateWillReturnFalseWhenMaxPerPageIsNull(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setMaxPerPage(null);

        $this->assertFalse($pager->haveToPaginate());
    }

    public function testHaveToPaginate(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->haveToPaginate());
    }

    public function testHasPreviousPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->hasPreviousPage());
    }

    public function testGetPreviousPageWhenThereAreMultiplePages(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));
        $pager->setCurrentPage(2);

        $this->assertSame(1, $pager->getPreviousPage());
    }

    public function testGetPreviousPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertNull($pager->getPreviousPage());
    }

    public function testHasNextPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertFalse($pager->hasNextPage());
    }

    public function testGetNextPageWhenThereAreMultiplePages(): void
    {
        $pager = new Pager(new ArrayAdapter(range(1, 100)));

        $this->assertSame(2, $pager->getNextPage());
    }

    public function testGetNextPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertNull($pager->getNextPage());
    }

    public function testGetCurrentPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(1, $pager->getCurrentPage());
    }

    public function testSetCurrentPageWithInvalidArgument(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->expectException('InvalidArgumentException');
        $pager->setCurrentPage(0);
    }

    public function testSetCurrentPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setCurrentPage(2);

        $this->assertSame(2, $pager->getCurrentPage());
    }

    public function testGetMaxPerPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame(10, $pager->getMaxPerPage());
    }

    public function testSetMaxPerPageWillTakeNull(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $pager->setMaxPerPage(null);
        $this->assertNull($pager->getMaxPerPage());
    }

    public function testSetMaxPerPageWithInvalidArgument(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->expectException('InvalidArgumentException');
        $pager->setMaxPerPage(0);
    }

    public function testSetMaxPerPage(): void
    {
        $pager = new Pager(new ArrayAdapter([]));
        $pager->setMaxPerPage(25);

        $this->assertSame(25, $pager->getMaxPerPage());
    }

    public function testCount(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertEmpty($pager);
        $this->assertEmpty($pager);
    }

    public function testGetIteratorWhenGenerator(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn(): int => 0,
            slice: function (): Generator {
                yield new stdClass();
                yield new stdClass();
            },
        ));

        $this->assertInstanceOf('Generator', $pager->getIterator());
    }

    public function testGetIteratorWhenIteratorAggregateIsReturned(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn(): int => 1,
            slice: fn(): IteratorAggregate => new class implements IteratorAggregate {
                public function getIterator(): Traversable
                {
                    return new ArrayIterator([]);
                }
            },
        ));

        $this->assertInstanceOf('ArrayIterator', $pager->getIterator());
    }

    public function testGetIteratorWhenIteratorIsReturned(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn(): int => 1,
            slice: fn(): ArrayIterator => new ArrayIterator([]),
        ));

        $this->assertInstanceOf('ArrayIterator', $pager->getIterator());
    }

    public function testGetIterator(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertInstanceOf('Traversable', $pager->getIterator());
    }

    public function testJsonSerializeWhenResultsAreTraversable(): void
    {
        $pager = new Pager(new CallableAdapter(
            count: fn(): int => 2,
            slice: function (): Generator {
                yield new stdClass();
                yield new stdClass();
            },
        ));

        $this->assertSame('[{},{}]', json_encode($pager));
    }

    public function testJsonSerialize(): void
    {
        $pager = new Pager(new ArrayAdapter([]));

        $this->assertSame('[]', json_encode($pager));
    }
}
