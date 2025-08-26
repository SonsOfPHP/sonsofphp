<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

/**
 * Defines methods for encrypting and decrypting secrets.
 */
interface CipherInterface
{
    /**
     * Encrypts plaintext using the provided key.
     */
    public function encrypt(string $plaintext, string $key): string;

    /**
     * Decrypts ciphertext using the provided key.
     */
    public function decrypt(string $ciphertext, string $key): string;
}
