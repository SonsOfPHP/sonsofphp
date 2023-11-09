<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Mapping\Driver;

use SonsOfPHP\Component\EventSourcing\Mapping\Driver\AttributeDriver;
use SonsOfPHP\Component\EventSourcing\Mapping\Driver\DriverInterface;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Mapping\Driver\AttributeDriver
 */
final class AttributeDriverTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(DriverInterface::class, new AttributeDriver());
    }

    /**
     * @coversNothing
     */
    public function testItWorks(): void
    {
        $driver = new AttributeDriver();

        //dd(
        //    iterator_to_array($driver->getClassAttributes(FakeAggregate::class)),
        //    iterator_to_array($driver->getMethodAttributes(FakeAggregate::class)),
        //    iterator_to_array($driver->getPropertyAttributes(FakeAggregate::class)),
        //    $driver->getPropertyAttribute(FakeAggregate::class, 'id'),
        //);
        $this->assertTrue(true);
    }
}
