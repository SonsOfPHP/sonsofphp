<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Exception;

/**
 * Thrown when the stored secret data is malformed or corrupted.
 */
class SecretStorageCorruptedException extends VaultException {}
