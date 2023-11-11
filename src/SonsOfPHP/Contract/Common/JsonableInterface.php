<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Common;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface JsonableInterface
{
    /**
     * Returns a json string of the object
     *
     * @see https://www.php.net/json_encode
     */
    public function toJson(int $flags = 0, int $depth = 512): string;
}
