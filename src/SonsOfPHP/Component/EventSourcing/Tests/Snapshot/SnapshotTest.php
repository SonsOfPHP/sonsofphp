<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Snapshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Snapshot\Snapshot;
use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;

/**
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @coversNothing
 */
#[CoversClass(Snapshot::class)]
final class SnapshotTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $snapshot = new Snapshot(AggregateId::fromString('id'), AggregateVersion::fromInt(10), '');
        $this->assertInstanceOf(SnapshotInterface::class, $snapshot);
    }

    public function testGetters(): void
    {
        $snapshot = new Snapshot(AggregateId::fromString('id'), AggregateVersion::fromInt(10), 'empty state');

        $this->assertSame('id', $snapshot->getAggregateId()->toString());
        $this->assertSame(10, $snapshot->getAggregateVersion()->toInt());
        $this->assertSame('empty state', $snapshot->getState());
    }
}
