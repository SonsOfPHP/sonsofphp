<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault;

use RuntimeException;
use SonsOfPHP\Component\Vault\Cipher\CipherInterface;
use SonsOfPHP\Component\Vault\Storage\StorageInterface;

/**
 * Vault stores encrypted secrets using a pluggable storage backend with
 * support for additional authenticated data and key rotation.
 */
class Vault
{
    /**
     * @param StorageInterface       $storage       The storage backend.
     * @param CipherInterface        $cipher        The cipher used for encryption.
     * @param array<string,string>   $keys          Map of key IDs to encryption keys.
     * @param string                 $currentKeyId  Identifier of the active key.
     */
    public function __construct(private readonly StorageInterface $storage, private readonly CipherInterface $cipher, private array $keys, private string $currentKeyId)
    {
    }

    /**
     * Stores a secret in the vault.
     *
     * @param string $name  Identifier of the secret.
     * @param mixed  $secret The secret to store.
     * @param string $aad   Additional authenticated data.
     */
    public function set(string $name, mixed $secret, string $aad = ''): void
    {
        $serialized = serialize($secret);
        $key        = $this->keys[$this->currentKeyId];
        $encrypted  = $this->cipher->encrypt($serialized, $key, $aad);
        $this->storage->set($name, $this->currentKeyId . ':' . $encrypted);
    }

    /**
     * Retrieves a secret from the vault or null if it does not exist.
     *
     * @param string $name Identifier of the secret.
     * @param string $aad  Additional authenticated data.
     *
     * @return mixed|null
     */
    public function get(string $name, string $aad = ''): mixed
    {
        $record = $this->storage->get($name);
        if (null === $record) {
            return null;
        }

        [$keyId, $ciphertext] = explode(':', $record, 2);
        $key = $this->keys[$keyId] ?? null;
        if (null === $key) {
            throw new RuntimeException('Unknown key identifier.');
        }

        $serialized = $this->cipher->decrypt($ciphertext, $key, $aad);

        $secret = @unserialize($serialized, ['allowed_classes' => false]);
        if (false === $secret && 'b:0;' !== $serialized) {
            throw new RuntimeException('Unable to unserialize secret.');
        }

        return $secret;
    }

    /**
     * Removes a secret from the vault.
     */
    public function delete(string $name): void
    {
        $this->storage->delete($name);
    }

    /**
     * Rotates the active encryption key.
     *
     * @param string $keyId Identifier for the new key.
     * @param string $key   The new encryption key.
     */
    public function rotateKey(string $keyId, string $key): void
    {
        $this->keys[$keyId] = $key;
        $this->currentKeyId = $keyId;
    }
}
