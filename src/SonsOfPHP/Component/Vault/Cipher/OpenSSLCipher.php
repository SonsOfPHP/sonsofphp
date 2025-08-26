<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

use RuntimeException;

/**
 * Encrypts and decrypts secrets using OpenSSL.
 */
class OpenSSLCipher implements CipherInterface
{
    /**
     * @param string $cipherMethod OpenSSL cipher method supporting AEAD.
     */
    public function __construct(private readonly string $cipherMethod = 'aes-256-gcm') {}

    public function encrypt(string $plaintext, string $key, string $aad = ''): string
    {
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv       = random_bytes($ivLength);
        $tag      = '';
        $encrypted = openssl_encrypt($plaintext, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv, $tag, $aad, 16);
        if (false === $encrypted) {
            throw new RuntimeException('Unable to encrypt secret.');
        }

        return base64_encode($iv . $tag . $encrypted);
    }

    public function decrypt(string $ciphertext, string $key, string $aad = ''): string
    {
        $data = base64_decode($ciphertext, true);
        if (false === $data) {
            throw new RuntimeException('Invalid ciphertext.');
        }

        $ivLength  = openssl_cipher_iv_length($this->cipherMethod);
        $tagLength = 16;
        $iv        = substr($data, 0, $ivLength);
        $tag       = substr($data, $ivLength, $tagLength);
        $payload   = substr($data, $ivLength + $tagLength);
        $decrypted = openssl_decrypt($payload, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv, $tag, $aad);
        if (false === $decrypted) {
            throw new RuntimeException('Unable to decrypt secret.');
        }

        return $decrypted;
    }
}
