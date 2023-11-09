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
    private ContainerInterface $container;
    private DriverInterface $driver;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        array $config = [],
    ) {
        if (array_key_exists('container', $config)) {
            $this->container = $config['container'];
        }

        if (array_key_exists('driver', $config)) {
            $this->driver = $config['driver'];
        } else {
            $this->driver = new AttributeDriver();
        }

        if (array_key_exists('event_dispatcher', $config)) {
            $this->eventDispatcher = $config['event_dispatcher'];
        } else {
            $this->eventDispatcher = new EventDispatcher();
        }
    }

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
