<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

/**
 * Defines methods for encrypting and decrypting secrets.
 */
interface CipherInterface
{
    /**
     * Encrypts plaintext using the provided key and optional authenticated data.
     */
    public function encrypt(string $plaintext, string $key, string $aad = ''): string;

    /**
     * Decrypts ciphertext using the provided key and optional authenticated data.
     */
    public function decrypt(string $ciphertext, string $key, string $aad = ''): string;
}
