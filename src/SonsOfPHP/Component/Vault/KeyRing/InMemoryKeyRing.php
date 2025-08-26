<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\KeyRing;

use SensitiveParameter;

/**
 * Simple key ring that keeps keys in memory.
 */
class InMemoryKeyRing implements KeyRingInterface
{
    /**
     * @param array<string, string> $keys Map of key IDs to keys.
     * @param string                $currentKeyId Identifier of the active key.
     */
    public function __construct(private array $keys, private string $currentKeyId) {}

    public function getCurrentKeyId(): string
    {
        return $this->currentKeyId;
    }

    public function getCurrentKey(): string
    {
        return $this->keys[$this->currentKeyId];
    }

    public function getKey(string $keyId): ?string
    {
        return $this->keys[$keyId] ?? null;
    }

    public function rotate(string $keyId, #[SensitiveParameter] string $key): void
    {
        $this->keys[$keyId] = $key;
        $this->currentKeyId = $keyId;
    }
}
