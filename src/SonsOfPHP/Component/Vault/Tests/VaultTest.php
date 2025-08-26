<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

/**
 * @covers \SonsOfPHP\Component\Vault\Vault
 * @internal
 */
class VaultTest extends TestCase
{
    /**
     * Creates a vault instance for testing.
     */
    private function createVault(): Vault
    {
        $storage = new InMemoryStorage();
        $cipher  = new OpenSSLCipher();
        $key     = 'test_encryption_key_32_bytes!';

        return new Vault($storage, $cipher, $key);
    }

    public function testSecretCanBeRetrieved(): void
    {
        $vault = $this->createVault();
        $vault->set('db_password', 'secret');

        $this->assertSame('secret', $vault->get('db_password'));
    }

    public function testGetReturnsNullWhenSecretIsMissing(): void
    {
        $vault = $this->createVault();

        $this->assertNull($vault->get('missing'));
    }

    public function testSecretCanBeDeleted(): void
    {
        $vault = $this->createVault();
        $vault->set('api_key', 'secret');
        $vault->delete('api_key');

        $this->assertNull($vault->get('api_key'));
    }
}
