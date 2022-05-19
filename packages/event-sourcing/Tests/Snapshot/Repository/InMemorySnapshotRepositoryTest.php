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
     * @covers ::persist
     * @covers ::find
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
