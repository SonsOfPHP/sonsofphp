<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Common;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ArrayableInterface
{
    public function toArray(): array;
}
