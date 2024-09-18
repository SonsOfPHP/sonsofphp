<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Marshaller;

use SonsOfPHP\Component\Cache\Exception\CacheException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MarshallerInterface
{
    /**
     * Serialize PHP value and return a string that can be stored
     */
    public function marshall(mixed $value): string;

    /**
     * Unserialize the stored value
     *
     * @throws CacheException
     */
    public function unmarshall(string $value): mixed;
}
