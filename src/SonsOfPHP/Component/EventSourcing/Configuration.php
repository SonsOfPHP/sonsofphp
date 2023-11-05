<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventSourcing\Mapping\Driver\DriverInterface;
use SonsOfPHP\Component\EventSourcing\Mapping\Driver\AttributeDriver;
use SonsOfPHP\Component\EventDispatcher\EventDispatcher;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function __construct(
        private array $paths = [],
        private DriverInterface $driver = new AttributeDriver(),
        private EventDispatcherInterface $eventDispatcher = new EventDispatcher(),
    ) {}

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }
}
