<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsNegativeMoneyQuery implements MoneyQueryInterface
{
    public function queryFrom(MoneyInterface $money): bool
    {
        return $money->getAmount()->isNegative();
    }
}
