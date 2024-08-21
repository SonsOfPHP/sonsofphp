<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;

/**
 * Null Adapter does absolutly nothing, it's good for testing and that's pretty
 * much it.
 *
 * Usage:
 *   $adapter = new NullAdapter();
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullAdapter implements AdapterInterface
{
    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void {}

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        return '';
    }

    public function remove(string $path, ?ContextInterface $context = null): void {}

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        return false;
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        return false;
    }
}
