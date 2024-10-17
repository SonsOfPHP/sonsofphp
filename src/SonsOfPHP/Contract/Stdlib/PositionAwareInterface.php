<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * Many times, a record may be part of a group and that group needs to have a
 * user defined order. Having a "position" allows you to order records from a
 * database based on what the user has defined.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PositionAwareInterface
{
    /**
     * The implementing library MUST define a default value for the position.
     * This is generally a 0.
     */
    public function getPosition(): int;

    /**
     * A position can be a positive or negative integer
     */
    public function setPosition(int $position): void;
}
