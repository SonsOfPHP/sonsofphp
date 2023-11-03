<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Snapshot\Repository;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Snapshot\Repository\InMemorySnapshotRepository;
use SonsOfPHP\Component\EventSourcing\Snapshot\Repository\SnapshotRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Snapshot\Snapshot;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Snapshot\Repository\InMemorySnapshotRepository
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @uses \SonsOfPHP\Component\EventSourcing\Snapshot\Snapshot
 */
final class InMemorySnapshotRepositoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $repository = new InMemorySnapshotRepository();
        $this->assertInstanceOf(SnapshotRepositoryInterface::class, $repository);
    }

    /**
     * @covers ::find
     * @covers ::persist
     */
    public function testPersistAndFind(): void
    {
        $repository = new InMemorySnapshotRepository();

        $snapshot = new Snapshot(AggregateId::fromString('id'), AggregateVersion::fromInt(10), 'empty state');
        $repository->persist($snapshot);

        $result = $repository->find(AggregateId::fromString('id'));
        $this->assertSame($snapshot, $result);
    }

    /**
     * @covers ::find
     */
    public function testFindReturnsNullIfNoSnapshotFound(): void
    {
        $repository = new InMemorySnapshotRepository();

        $this->assertNull($repository->find(AggregateId::fromString('id')));
    }
}
