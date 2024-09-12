<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Registry;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ServiceRegistryInterface
{
    /**
     * Returns all the services registered
     *   key = identifier
     *   value = service
     */
    public function all(): iterable;

    /**
     * @throws ExistingServiceExceptionInterface
     * @throws \InvalidArgumentException
     *   If the $service does not implement the service interface
     */
    public function register(string $identifier, object $service): void;

    /**
     * @throws NonExistingServiceExceptionInterface
     */
    public function unregister(string $identifier): void;

    /**
     * Returns true if service exists.
     */
    public function has(string $identifier): bool;

    /**
     * @throws NonExistingServiceExceptionInterface
     */
    public function get(string $identifier): object;
}
