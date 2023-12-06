<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Container;

use SonsOfPHP\Component\Container\Exception\ContainerException;
use SonsOfPHP\Component\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Usage:
 *   $container = new Container();
 *   $container->set('service.id', function (ContainerInterface $container) {
 *       return new Service($container->get('another.service_id'));
 *   });
 */
class Container implements ContainerInterface
{
    private array $services = [];
    private array $cachedServices = [];

    /**
     * {@inheritdoc}
     */
    public function get(string $id)
    {
        if (false === $this->has($id)) {
            throw new NotFoundException(sprintf('Service "%s" not found.', $id));
        }

        if (!array_key_exists($id, $this->cachedServices)) {
            $this->cachedServices[$id] = call_user_func($this->services[$id], $this);
        }

        return $this->cachedServices[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param callable $callable
     */
    public function set(string $id, $callable): self
    {
        if (!is_callable($callable)) {
            throw new ContainerException('MUST pass in a callable');
        }

        $this->services[$id] = $callable;

        return $this;
    }
}
