<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Common;

use RuntimeException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ComparableInterface
{
    /**
     * Returns -1 if $this  <  $other
     * Returns  0 if $this === $other
     * Returns  1 if $this  >  $other
     *
     *
     * @throws RuntimeException When the two objects cannot be compared
     */
    public function compare(mixed $other): int;
}
