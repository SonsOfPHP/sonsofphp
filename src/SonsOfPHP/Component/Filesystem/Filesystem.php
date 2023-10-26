<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem;

use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Component\Filesystem\Adapter\MoveAwareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Filesystem implements FilesystemInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function write(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        $this->adapter->add($path, $contents);
    }

    public function read(string $path, ?ContextInterface $context = null): string
    {
        return $this->adapter->get($path);
    }

    public function delete(string $path, ?ContextInterface $context = null): void
    {
        $this->adapter->remove($path, $context);
    }

    public function exists(string $path, ?ContextInterface $context = null): bool
    {
        $this->adapter->has($path, $context);
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        if ($this->adapter instanceof CopyAwareInterface) {
            $this->adapter->copy($source, $destination);
            return;
        }

        $this->write($destination, $this->get($source, $context), $context);
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        if ($this->adapter instanceof MoveAwareInterface) {
            $this->adapter->move($source, $destination, $context);
            return;
        }

        $this->copy($source, $destination, $context);
        $this->delete($source, $context);
    }
}
