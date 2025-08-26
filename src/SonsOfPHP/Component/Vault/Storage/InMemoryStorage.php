<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Storage;

/**
 * Simple in-memory storage for secrets.
 */
class InMemoryStorage implements StorageInterface
{
    /**
     * @var array<string, string>
     */
    private array $secrets = [];

    public function set(string $name, string $value): void
    {
        $this->secrets[$name] = $value;
    }

    public function get(string $name): ?string
    {
        return $this->secrets[$name] ?? null;
    }

    public function delete(string $name): void
    {
        unset($this->secrets[$name]);
    }
}
