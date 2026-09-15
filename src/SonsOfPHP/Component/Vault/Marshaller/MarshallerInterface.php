<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Marshaller;

use SonsOfPHP\Component\Vault\Exception\MarshallingException;

/**
 * Marshals PHP values to and from storable strings.
 */
interface MarshallerInterface
{
    /**
     * Converts a PHP value to a string for storage.
     */
    public function marshall(mixed $value): string;

    /**
     * Restores a PHP value from stored data.
     *
     * @throws MarshallingException
     */
    public function unmarshall(string $value): mixed;
}
