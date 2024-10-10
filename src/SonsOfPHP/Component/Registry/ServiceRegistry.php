<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Registry;

use SonsOfPHP\Component\Registry\Exception\ExistingServiceException;
use SonsOfPHP\Component\Registry\Exception\NonExistingServiceException;
use SonsOfPHP\Contract\Registry\ServiceRegistryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ServiceRegistry implements ServiceRegistryInterface
{
    private array $services = [];

    public function __construct(
        private readonly string $interface,
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
            throw new ExistingServiceException(sprintf('Service "%s" already exists', $identifier));
        }

        if (!$service instanceof $this->interface) {
            throw new \InvalidArgumentException(sprintf(
                'Wrong Service Type. Expected "%s" got "%s"',
                $this->interface,
                $service::class
            ));
        }

        $this->services[$identifier] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(string $identifier): void
    {
        if (!$this->has($identifier)) {
            throw new NonExistingServiceException(sprintf('Service "%s" does not exist', $identifier));
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
            throw new NonExistingServiceException(sprintf('Service "%s" does not exist', $identifier));
        }

        return $this->services[$identifier];
    }
}
