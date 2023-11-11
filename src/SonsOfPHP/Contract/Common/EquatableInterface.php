<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Common;

/**
 * Equatable Interface is used for objects that can be checked to see if they
 * are equal to each other.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EquatableInterface
{
    /**
     * Checks object to see if it is equal to another object.
     *
     * @param mixed $other
     * @param bool $strict
     *   When true, this MUST compare objects using === and when false objects
     *   MUST be compared using ==
     *
     * @throws \InvalidArgumentException
     *   If $other is unexpected type
     *
     * @throws \RuntimeException
     *   When it cannot be determined if the objects are equal to each other
     */
    public function equals($other, bool $strict = true): bool;
}
