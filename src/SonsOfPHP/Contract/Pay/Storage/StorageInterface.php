<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay\Storage;

/**
 * Storage is where all the models are stored. This could be the filesystem or
 * database.
 *
 * @template T of object
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface StorageInterface
{
    /**
     * @param T $model
     */
    public function save(object $model): void;

    /**
     * @param T $model
     */
    public function remove(object $model): void;

    /**
     * @param IndentifierInterface|string|int $id
     *
     * @return T
     */
    public function find($id): ?object;
}
