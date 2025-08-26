<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Storage;

/**
 * Defines methods for persisting secrets.
 */
interface StorageInterface
{
    /**
     * Stores an encrypted secret.
     */
    public function set(string $name, string $value): void;

    /**
     * Retrieves an encrypted secret or null if it does not exist.
     */
    public function get(string $name): ?string;

    /**
     * Deletes a secret.
     */
    public function delete(string $name): void;
}
