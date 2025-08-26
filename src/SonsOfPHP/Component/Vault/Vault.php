<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault;

use SonsOfPHP\Component\Vault\Cipher\CipherInterface;
use SonsOfPHP\Component\Vault\Storage\StorageInterface;

/**
 * Vault stores encrypted secrets using a pluggable storage backend.
 */
class Vault
{
    /**
     * @param StorageInterface $storage The storage backend.
     * @param CipherInterface $cipher  The cipher used for encryption.
     * @param string $encryptionKey The encryption key.
     */
    public function __construct(private readonly StorageInterface $storage, private readonly CipherInterface $cipher, private readonly string $encryptionKey)
    {
    }

    /**
     * Stores a secret in the vault.
     */
    public function set(string $name, string $secret): void
    {
        $encrypted = $this->cipher->encrypt($secret, $this->encryptionKey);
        $this->storage->set($name, $encrypted);
    }

    /**
     * Retrieves a secret from the vault or null if it does not exist.
     */
    public function get(string $name): ?string
    {
        $encrypted = $this->storage->get($name);
        if (null === $encrypted) {
            return null;
        }

        return $this->cipher->decrypt($encrypted, $this->encryptionKey);
    }

    /**
     * Removes a secret from the vault.
     */
    public function delete(string $name): void
    {
        $this->storage->delete($name);
    }
}
