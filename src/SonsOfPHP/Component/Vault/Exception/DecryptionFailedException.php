<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Exception;

/**
 * Thrown when a value cannot be decrypted.
 */
class DecryptionFailedException extends CipherException {}
