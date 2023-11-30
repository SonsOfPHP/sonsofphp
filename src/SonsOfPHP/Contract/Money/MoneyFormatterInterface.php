<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyFormatterInterface
{
    public function format(MoneyInterface $money): string;
}
