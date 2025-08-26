<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault;

use JsonException;
use SensitiveParameter;
use SonsOfPHP\Component\Vault\Cipher\CipherInterface;
use SonsOfPHP\Component\Vault\Exception\MarshallingException;
use SonsOfPHP\Component\Vault\Exception\SecretStorageCorruptedException;
use SonsOfPHP\Component\Vault\Exception\UnknownKeyException;
use SonsOfPHP\Component\Vault\KeyRing\KeyRingInterface;
use SonsOfPHP\Component\Vault\Marshaller\MarshallerInterface;
use SonsOfPHP\Component\Vault\Storage\StorageInterface;

/**
 * Vault stores encrypted secrets using a pluggable storage backend with
 * support for additional authenticated data and key rotation.
 */
class Vault
{
    /**
     * @param StorageInterface   $storage    The storage backend.
     * @param CipherInterface    $cipher     The cipher used for encryption.
     * @param KeyRingInterface   $keyRing    Provides access to encryption keys.
     * @param MarshallerInterface $marshaller Converts secrets to storable strings.
     */
    public function __construct(private readonly StorageInterface $storage, private readonly CipherInterface $cipher, private readonly KeyRingInterface $keyRing, private readonly MarshallerInterface $marshaller) {}

    /**
     * Stores a secret in the vault.
     *
     * @param string                  $name   Identifier of the secret.
     * @param mixed                   $secret The secret to store.
     * @param array<array-key, mixed> $aad    Additional authenticated data.
     *
     * @throws MarshallingException
     * @throws SecretStorageCorruptedException
     */
    public function set(string $name, #[SensitiveParameter] mixed $secret, array $aad = []): void
    {
        $encoded   = $this->marshaller->marshall($secret);
        $key       = $this->keyRing->getCurrentKey();
        $encrypted = $this->cipher->encrypt($encoded, $key, $aad);

        $record = $this->storage->get($name);
        if (null === $record) {
            $versions = [];
        } else {
            try {
                $versions = json_decode($record, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                throw new SecretStorageCorruptedException('Invalid secret storage format.', 0, $e);
            }

            if (!is_array($versions)) {
                throw new SecretStorageCorruptedException('Invalid secret storage format.');
            }
        }

        $version                     = $versions === [] ? 1 : max(array_map('intval', array_keys($versions))) + 1;
        $versions[(string) $version] = $this->keyRing->getCurrentKeyId() . ':' . $encrypted;
        $this->storage->set($name, json_encode($versions, JSON_THROW_ON_ERROR));
    }

    /**
     * Retrieves a secret from the vault or null if it does not exist.
     *
     * @param string                  $name    Identifier of the secret.
     * @param array<array-key, mixed> $aad     Additional authenticated data.
     * @param int|null                $version Specific version to retrieve or null for latest.
     *
     * @return mixed|null
     *
     * @throws MarshallingException
     * @throws SecretStorageCorruptedException
     * @throws UnknownKeyException
     */
    public function get(string $name, array $aad = [], ?int $version = null): mixed
    {
        $record = $this->storage->get($name);
        if (null === $record) {
            return null;
        }

        try {
            $versions = json_decode($record, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new SecretStorageCorruptedException('Invalid secret storage format.', 0, $jsonException);
        }

        if (!is_array($versions)) {
            throw new SecretStorageCorruptedException('Invalid secret storage format.');
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
            throw new UnknownKeyException(sprintf('Unknown key identifier "%s".', $keyId));
        }

        $encoded = $this->cipher->decrypt($ciphertext, $key, $aad);

        return $this->marshaller->unmarshall($encoded);
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
