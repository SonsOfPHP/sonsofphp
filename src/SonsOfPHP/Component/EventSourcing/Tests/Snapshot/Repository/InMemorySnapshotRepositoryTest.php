<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Snapshot\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Snapshot\Repository\InMemorySnapshotRepository;
use SonsOfPHP\Component\EventSourcing\Snapshot\Repository\SnapshotRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Snapshot\Snapshot;

#[CoversClass(InMemorySnapshotRepository::class)]
#[UsesClass(AbstractAggregateId::class)]
#[UsesClass(AggregateVersion::class)]
#[UsesClass(Snapshot::class)]
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

    public function testPersistAndFind(): void
    {
        $repository = new InMemorySnapshotRepository();

        $snapshot = new Snapshot(AggregateId::fromString('id'), AggregateVersion::fromInt(10), 'empty state');
        $repository->persist($snapshot);

        $result = $repository->find(AggregateId::fromString('id'));
        $this->assertSame($snapshot, $result);
    }

    public function testFindReturnsNullIfNoSnapshotFound(): void
    {
        $repository = new InMemorySnapshotRepository();

        $this->assertNull($repository->find(AggregateId::fromString('id')));
    }
}
