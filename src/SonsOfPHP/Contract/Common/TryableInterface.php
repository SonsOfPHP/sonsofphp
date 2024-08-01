<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Common;

use InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TryableInterface
{
    /**
     * Pass in $data and the object is built.
     *
     * @throws InvalidArgumentException
     *   If $data is invalid
     */
    public static function from(mixed $data): static;

    /**
     * Pass in data and if the data is invalid it will return null
     */
    public function tryFrom(mixed $data): ?static;
}
