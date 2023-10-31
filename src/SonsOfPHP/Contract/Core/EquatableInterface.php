<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Core;

/**
 * Equatable Interface is used for objects that can be checked to see if they
 * are equal to each other.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EquatableInterface
{
    /**
     * This needs to check to ensure both objects are equal to each other.
     * Depending on how this is implemented, it can be loose (==) or strict
     * (===).
     *
     * @param mixed $other
     *
     * @throws \RuntimeException
     *   When it cannot be determined if the objects are equal to each other
     */
    public function equals($other, bool $strict = false): bool;
}
