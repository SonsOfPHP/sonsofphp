<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

use RuntimeException;

/**
 * Encrypts and decrypts secrets using OpenSSL.
 */
class OpenSSLCipher implements CipherInterface
{
    public function __construct(private readonly string $cipherMethod = 'aes-256-ctr')
    {
    }

    public function encrypt(string $plaintext, string $key): string
    {
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv       = random_bytes($ivLength);
        $encrypted = openssl_encrypt($plaintext, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
        if (false === $encrypted) {
            throw new RuntimeException('Unable to encrypt secret.');
        }

        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        $data     = base64_decode($ciphertext, true);
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv       = substr($data, 0, $ivLength);
        $payload  = substr($data, $ivLength);
        $decrypted = openssl_decrypt($payload, $this->cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
        if (false === $decrypted) {
            throw new RuntimeException('Unable to decrypt secret.');
        }

        return $decrypted;
    }
}
