<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EnumInterface
{
    /**
     * This will return an array of all the values of the BackedEnum
     *
     * @return array<array-key, scalar>
     */
    public static function values(): array;
}
