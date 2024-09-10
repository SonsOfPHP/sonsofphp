<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @coversNothing
 */
#[CoversClass(AggregateVersion::class)]
final class AggregateVersionTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $version = new AggregateVersion();
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);

        $version = AggregateVersion::zero();
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);

        $version = AggregateVersion::fromInt(99);
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);
    }

    public function testDefaultVersionIsZero(): void
    {
        $version = new AggregateVersion();
        $this->assertSame(0, $version->toInt());
    }

    public function testVersionCanBePassedIntoConstructor(): void
    {
        $version = new AggregateVersion(420);
        $this->assertSame(420, $version->toInt());
    }

    public function testFromInt(): void
    {
        $version = AggregateVersion::fromInt(1);

        $this->assertSame(1, $version->toInt());
    }

    public function testZero(): void
    {
        $version = AggregateVersion::zero();

        $this->assertSame(0, $version->toInt());
    }

    public function testNext(): void
    {
        $v1 = AggregateVersion::zero()->next();
        $this->assertSame(1, $v1->toInt());

        $v2 = $v1->next();
        $this->assertSame(1, $v1->toInt());
        $this->assertSame(2, $v2->toInt());
    }

    public function testPrev(): void
    {
        $version = AggregateVersion::zero()->next()->prev();

        $this->assertSame(0, $version->toInt());
    }

    public function testEquals(): void
    {
        $versionA = AggregateVersion::zero();
        $versionB = AggregateVersion::zero();

        $this->assertTrue($versionA->equals($versionB));
        $this->assertTrue($versionB->equals($versionA));
    }

    public function testInvalidVersionUsingFromInt(): void
    {
        $this->expectException(EventSourcingException::class);

        AggregateVersion::fromInt(-1);
    }

    public function testInvalidVersionUsingConstructor(): void
    {
        $this->expectException(EventSourcingException::class);
        new AggregateVersion(-1);
    }
}
