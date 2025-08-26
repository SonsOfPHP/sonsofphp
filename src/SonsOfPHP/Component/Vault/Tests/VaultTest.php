<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\Exception\DecryptionFailedException;
use SonsOfPHP\Component\Vault\Exception\UnknownKeyException;
use SonsOfPHP\Component\Vault\KeyRing\InMemoryKeyRing;
use SonsOfPHP\Component\Vault\Marshaller\JsonMarshaller;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

#[CoversClass(Vault::class)]
class VaultTest extends TestCase
{
    /**
     * Creates a vault instance for testing.
     */
    private function createVault(?InMemoryStorage &$storage = null): Vault
    {
        $storage    ??= new InMemoryStorage();
        $cipher      = new OpenSSLCipher();
        $keyRing     = new InMemoryKeyRing(['v1' => 'test_encryption_key_32_bytes!'], 'v1');
        $marshaller  = new JsonMarshaller();

        return new Vault($storage, $cipher, $keyRing, $marshaller);
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

        $this->expectException(DecryptionFailedException::class);
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
        $versions = json_decode((string) $stored, true, 512, JSON_THROW_ON_ERROR);
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

    public function testGetThrowsWhenKeyIsUnknown(): void
    {
        $storage = new InMemoryStorage();
        $vault   = $this->createVault($storage);
        $vault->set('secret', 'value');

        $otherKeyRing = new InMemoryKeyRing(['v2' => 'another_32_byte_encryption_key!!'], 'v2');
        $marshaller   = new JsonMarshaller();
        $otherVault   = new Vault($storage, new OpenSSLCipher(), $otherKeyRing, $marshaller);

        $this->expectException(UnknownKeyException::class);
        $otherVault->get('secret');
    }
}
