<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\AggregateManager;
use SonsOfPHP\Component\EventSourcing\AggregateManagerInterface;
use SonsOfPHP\Component\EventSourcing\ConfigurationInterface;
use SonsOfPHP\Component\EventSourcing\Mapping\Driver\AttributeDriver;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;
use Psr\Container\ContainerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\AggregateManager
 */
final class AggregateManagerTest extends TestCase
{
    private $config;
    private $container;

    public function setUp(): void
    {
        $this->config = $this->createMock(ConfigurationInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(AggregateManagerInterface::class, new AggregateManager($this->config, $this->container));
    }

    /**
     * @coversNothing
     */
    public function testItWorks(): void
    {
        $this->config->method('getDriver')->willReturn(new AttributeDriver());

        $manager = new AggregateManager($this->config, $this->container);

        $manager->registerAggregate(FakeAggregate::class);





        $this->assertTrue(true);
    }
}
