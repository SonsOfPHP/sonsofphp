<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Contract\Money\Query\Money\MoneyQueryInterface;
use SonsOfPHP\Contract\Money\MoneyInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsNegativeMoneyQuery implements MoneyQueryInterface
{
    public function queryFrom(MoneyInterface $money)
    {
        return $money->getAmount()->isNegative();
    }
}
