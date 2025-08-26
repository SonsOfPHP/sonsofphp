<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault;

use RuntimeException;
use SensitiveParameter;
use SonsOfPHP\Component\Vault\Cipher\CipherInterface;
use SonsOfPHP\Component\Vault\KeyRing\KeyRingInterface;
use SonsOfPHP\Component\Vault\Storage\StorageInterface;

/**
 * Vault stores encrypted secrets using a pluggable storage backend with
 * support for additional authenticated data and key rotation.
 */
class Vault
{
    /**
     * @param StorageInterface $storage The storage backend.
     * @param CipherInterface  $cipher  The cipher used for encryption.
     * @param KeyRingInterface $keyRing Provides access to encryption keys.
     */
    public function __construct(private readonly StorageInterface $storage, private readonly CipherInterface $cipher, private readonly KeyRingInterface $keyRing) {}

    /**
     * Stores a secret in the vault.
     *
     * @param string                   $name   Identifier of the secret.
     * @param mixed                    $secret The secret to store.
     * @param array<array-key, mixed>  $aad    Additional authenticated data.
     */
    public function set(string $name, #[SensitiveParameter] mixed $secret, array $aad = []): void
    {
        $serialized = serialize($secret);
        $key        = $this->keyRing->getCurrentKey();
        $encrypted  = $this->cipher->encrypt($serialized, $key, $aad);

        $record = $this->storage->get($name);
        if (null === $record) {
            $versions = [];
        } else {
            $versions = @unserialize($record, ['allowed_classes' => false]);
            if (!is_array($versions)) {
                throw new RuntimeException('Invalid secret storage format.');
            }
        }

        $version                     = $versions === [] ? 1 : max(array_map('intval', array_keys($versions))) + 1;
        $versions[(string) $version] = $this->keyRing->getCurrentKeyId() . ':' . $encrypted;
        $this->storage->set($name, serialize($versions));
    }

    /**
     * Retrieves a secret from the vault or null if it does not exist.
     *
     * @param string                  $name    Identifier of the secret.
     * @param array<array-key, mixed> $aad     Additional authenticated data.
     * @param int|null                $version Specific version to retrieve or null for latest.
     *
     * @return mixed|null
     */
    public function get(string $name, array $aad = [], ?int $version = null): mixed
    {
        $record = $this->storage->get($name);
        if (null === $record) {
            return null;
        }

        $versions = @unserialize($record, ['allowed_classes' => false]);
        if (!is_array($versions)) {
            throw new RuntimeException('Invalid secret storage format.');
        }

        if (null === $version) {
            $version = (int) max(array_map('intval', array_keys($versions)));
        }

        $entry = $versions[(string) $version] ?? null;
        if (null === $entry) {
            return null;
        }

        [$keyId, $ciphertext] = explode(':', (string) $entry, 2);
        $key                   = $this->keyRing->getKey($keyId);
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
    public function rotateKey(string $keyId, #[SensitiveParameter] string $key): void
    {
        $this->keyRing->rotate($keyId, $key);
    }
}
