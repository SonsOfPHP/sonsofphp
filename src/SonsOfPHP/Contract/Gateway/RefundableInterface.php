<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface RefundableInterface
{
    public function refund($options): void;
}
