<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Registry;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PrioritizedServiceRegistryInterface
{
    /**
     * Returns all the services registered
     *   key = identifier
     *   value = services
     *
     * The services MUST be order based on priorities
     */
    public function all(): iterable;

    /**
     * Priorities MUST be ordered from largest number to lowest number.
     *
     * The default priority MUST be 0
     *
     * @throws ExistingServiceExceptionInterface
     * @throws \InvalidArgumentException
     *   If the $service does not implement the service interface
     */
    public function register(string $identifier, object $service, int $priority = 0): void;

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
    public function get(string $identifier): iterable;
}
