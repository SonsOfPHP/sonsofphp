<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

use SensitiveParameter;
use SonsOfPHP\Component\Vault\Exception\DecryptionFailedException;
use SonsOfPHP\Component\Vault\Exception\EncryptionFailedException;
use SonsOfPHP\Component\Vault\Exception\InvalidCiphertextException;

/**
 * Encrypts and decrypts secrets using OpenSSL.
 */
class OpenSSLCipher implements CipherInterface
{
    /**
     * @param string $cipherMethod OpenSSL cipher method supporting AEAD.
     */
    public function __construct(private readonly string $cipherMethod = 'aes-256-gcm') {}

    /**
     * @param array<array-key, mixed> $aad Additional authenticated data.
     *
     * @throws EncryptionFailedException
     */
    public function encrypt(#[SensitiveParameter] string $plaintext, #[SensitiveParameter] string $key, array $aad = []): string
    {
        $ivLength   = openssl_cipher_iv_length($this->cipherMethod);
        $iv         = random_bytes($ivLength);
        $tag        = '';
        $encodedAad = json_encode($aad);
        $encrypted  = openssl_encrypt($plaintext, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv, $tag, $encodedAad, 16);
        if (false === $encrypted) {
            throw new EncryptionFailedException('Unable to encrypt secret.');
        }

        return base64_encode($iv . $tag . $encrypted);
    }

    /**
     * @param array<array-key, mixed> $aad Additional authenticated data.
     *
     * @throws InvalidCiphertextException
     * @throws DecryptionFailedException
     */
    public function decrypt(#[SensitiveParameter] string $ciphertext, #[SensitiveParameter] string $key, array $aad = []): string
    {
        $data = base64_decode($ciphertext, true);
        if (false === $data) {
            throw new InvalidCiphertextException('Invalid ciphertext.');
        }

        $ivLength  = openssl_cipher_iv_length($this->cipherMethod);
        $tagLength = 16;
        $iv         = substr($data, 0, $ivLength);
        $tag        = substr($data, $ivLength, $tagLength);
        $payload    = substr($data, $ivLength + $tagLength);
        $encodedAad = json_encode($aad);
        $decrypted  = openssl_decrypt($payload, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv, $tag, $encodedAad);
        if (false === $decrypted) {
            throw new DecryptionFailedException('Unable to decrypt secret.');
        }

        return $decrypted;
    }
}
