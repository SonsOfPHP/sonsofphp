<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Cipher;

use SensitiveParameter;

/**
 * Defines methods for encrypting and decrypting secrets.
 */
interface CipherInterface
{
    /**
     * Encrypts plaintext using the provided key and optional authenticated data.
     *
     * @param array<array-key, mixed> $aad Additional authenticated data.
     */
    public function encrypt(#[SensitiveParameter] string $plaintext, #[SensitiveParameter] string $key, array $aad = []): string;

    /**
     * Decrypts ciphertext using the provided key and optional authenticated data.
     *
     * @param array<array-key, mixed> $aad Additional authenticated data.
     */
    public function decrypt(#[SensitiveParameter] string $ciphertext, #[SensitiveParameter] string $key, array $aad = []): string;
}
