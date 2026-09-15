<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Exception;

/**
 * Thrown when ciphertext cannot be decoded before decryption.
 */
class InvalidCiphertextException extends CipherException {}
