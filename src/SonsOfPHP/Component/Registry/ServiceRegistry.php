<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Registry;

use SonsOfPHP\Contract\Registry\ServiceRegistryInterface;
use SonsOfPHP\Component\Registry\Exception\ExistingServiceException;
use SonsOfPHP\Component\Registry\Exception\NonExistingServiceException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ServiceRegistry implements ServiceRegistryInterface
{
    private array $services = [];

    public function __construct(
        private string $interface,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function all(): iterable
    {
        return $this->services;
    }

    /**
     * {@inheritdoc}
     */
    public function register(string $identifier, object $service): void
    {
        if ($this->has($identifier)) {
            throw new ExistingServiceException();
        }

        if (!$service instanceof $this->interface) {
            throw new \InvalidArgumentException();
        }

        $this->services[$identifier] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(string $identifier): void
    {
        if (!$this->has($identifier)) {
            throw new NonExistingServiceException();
        }

        unset($this->services[$identifier]);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $identifier): bool
    {
        return array_key_exists($identifier, $this->services);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $identifier): object
    {
        if (!$this->has($identifier)) {
            throw new NonExistingServiceException();
        }

        return $this->services[$identifier];
    }
}
