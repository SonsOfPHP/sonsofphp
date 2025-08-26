<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\KeyRing\InMemoryKeyRing;
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
    private function createVault(?InMemoryStorage &$storage = null): Vault
    {
        $storage ??= new InMemoryStorage();
        $cipher  = new OpenSSLCipher();
        $keyRing = new InMemoryKeyRing(['v1' => 'test_encryption_key_32_bytes!'], 'v1');

        return new Vault($storage, $cipher, $keyRing);
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

    public function testSetAndGetWithArray(): void
    {
        $vault = $this->createVault();
        $vault->set('config', ['user' => 'root']);

        $this->assertSame(['user' => 'root'], $vault->get('config'));
    }

    public function testSetAndGetWithAad(): void
    {
        $vault = $this->createVault();
        $vault->set('token', 'secret', ['aad']);

        $this->assertSame('secret', $vault->get('token', ['aad']));
    }

    public function testGetThrowsWhenAadDoesNotMatch(): void
    {
        $vault = $this->createVault();
        $vault->set('token', 'secret', ['aad']);

        $this->expectException(RuntimeException::class);
        $vault->get('token', ['bad']);
    }

    public function testSecretsEncryptedBeforeRotationAreStillAccessible(): void
    {
        $vault = $this->createVault();
        $vault->set('legacy', 'secret');
        $vault->rotateKey('v2', 'another_32_byte_encryption_key!!');

        $this->assertSame('secret', $vault->get('legacy'));
    }

    public function testRotateKeyChangesActiveKey(): void
    {
        $storage = new InMemoryStorage();
        $vault   = $this->createVault($storage);
        $vault->rotateKey('v2', 'another_32_byte_encryption_key!!');
        $vault->set('current', 'secret');

        $stored   = $storage->get('current');
        $versions = unserialize($stored, ['allowed_classes' => false]);
        $this->assertStringStartsWith('v2:', $versions['1']);
    }

    public function testGetReturnsLatestVersion(): void
    {
        $vault = $this->createVault();
        $vault->set('name', 'first');
        $vault->set('name', 'second');

        $this->assertSame('second', $vault->get('name'));
    }

    public function testSpecificVersionCanBeRetrieved(): void
    {
        $vault = $this->createVault();
        $vault->set('name', 'first');
        $vault->set('name', 'second');

        $this->assertSame('first', $vault->get('name', [], 1));
    }
}
