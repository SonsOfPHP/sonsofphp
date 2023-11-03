<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 */
final class AggregateVersionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::fromInt
     * @covers ::zero
     */
    public function testItHasTheRightInterface(): void
    {
        $version = new AggregateVersion();
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);

        $version = AggregateVersion::zero();
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);

        $version = AggregateVersion::fromInt(99);
        $this->assertInstanceOf(AggregateVersionInterface::class, $version);
    }

    /**
     * @covers ::__construct
     * @covers ::toInt
     */
    public function testDefaultVersionIsZero(): void
    {
        $version = new AggregateVersion();
        $this->assertSame(0, $version->toInt());
    }

    /**
     * @covers ::__construct
     * @covers ::toInt
     */
    public function testVersionCanBePassedIntoConstructor(): void
    {
        $version = new AggregateVersion(420);
        $this->assertSame(420, $version->toInt());
    }

    /**
     * @covers ::fromInt
     * @covers ::toInt
     */
    public function testFromInt(): void
    {
        $version = AggregateVersion::fromInt(1);

        $this->assertSame(1, $version->toInt());
    }

    /**
     * @covers ::toInt
     * @covers ::zero
     */
    public function testZero(): void
    {
        $version = AggregateVersion::zero();

        $this->assertSame(0, $version->toInt());
    }

    /**
     * @covers ::next
     */
    public function testNext(): void
    {
        $v1 = AggregateVersion::zero()->next();
        $this->assertSame(1, $v1->toInt());

        $v2 = $v1->next();
        $this->assertSame(1, $v1->toInt());
        $this->assertSame(2, $v2->toInt());
    }

    /**
     * @covers ::prev
     */
    public function testPrev(): void
    {
        $version = AggregateVersion::zero()->next()->prev();

        $this->assertSame(0, $version->toInt());
    }

    /**
     * @covers ::equals
     */
    public function testEquals(): void
    {
        $versionA = AggregateVersion::zero();
        $versionB = AggregateVersion::zero();

        $this->assertTrue($versionA->equals($versionB));
        $this->assertTrue($versionB->equals($versionA));
    }

    /**
     * @covers ::__construct
     * @covers ::fromInt
     * @covers ::isValid
     */
    public function testInvalidVersionUsingFromInt(): void
    {
        $this->expectException(EventSourcingException::class);

        $version = AggregateVersion::fromInt(-1);
    }

    /**
     * @covers ::__construct
     * @covers ::isValid
     */
    public function testInvalidVersionUsingConstructor(): void
    {
        $this->expectException(EventSourcingException::class);
        $version = new AggregateVersion(-1);
    }
}
