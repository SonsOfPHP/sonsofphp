<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Exception;

/**
 * Thrown when a key cannot be found in the key ring.
 */
class UnknownKeyException extends VaultException {}
