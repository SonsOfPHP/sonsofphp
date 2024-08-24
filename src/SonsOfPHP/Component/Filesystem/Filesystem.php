<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;
use SonsOfPHP\Contract\Filesystem\FilesystemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class Filesystem implements FilesystemInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function write(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        if (!is_string($contents) && !is_resource($contents)) {
            throw new FilesystemException(sprintf('Argument "$contents" must be of type "string" or "resource". Type "%s" given.', gettype($contents)));
        }

        $this->adapter->add($path, $contents, $context);
    }

    public function read(string $path, ?ContextInterface $context = null): string
    {
        return $this->adapter->get($path, $context);
    }

    public function delete(string $path, ?ContextInterface $context = null): void
    {
        $this->adapter->remove($path, $context);
    }

    public function exists(string $path, ?ContextInterface $context = null): bool
    {
        return $this->adapter->has($path, $context);
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

    public function mimeType(string $path, ?ContextInterface $context = null): string
    {
        return $this->adapter->mimeType($path, $context);
    }
}
