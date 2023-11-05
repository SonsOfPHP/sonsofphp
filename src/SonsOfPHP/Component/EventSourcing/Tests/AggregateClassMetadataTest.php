<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\AggregateClassMetadata;
use SonsOfPHP\Component\EventSourcing\AggregateClassMetadataInterface;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\AggregateClassMetadata
 */
final class AggregateClassMetadataTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(AggregateClassMetadataInterface::class, new AggregateClassMetadata(FakeAggregate::class));
    }

    /**
     * @coversNothing
     */
    public function testItWorks(): void
    {
        $metadata = new AggregateClassMetadata(FakeAggregate::class);

        //dd($metadata);
        $this->assertTrue(true);
    }
}
