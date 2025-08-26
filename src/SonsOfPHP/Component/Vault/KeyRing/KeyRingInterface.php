<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\KeyRing;

use SensitiveParameter;

/**
 * Manages encryption keys and their versions.
 */
interface KeyRingInterface
{
    /**
     * Returns the identifier for the current key.
     */
    public function getCurrentKeyId(): string;

    /**
     * Returns the current encryption key.
     */
    public function getCurrentKey(): string;

    /**
     * Fetches a key by its identifier or null if missing.
     */
    public function getKey(string $keyId): ?string;

    /**
     * Rotates to a new key and sets it as current.
     */
    public function rotate(string $keyId, #[SensitiveParameter] string $key): void;
}
