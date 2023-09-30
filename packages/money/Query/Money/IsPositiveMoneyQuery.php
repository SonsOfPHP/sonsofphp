<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Component\Money\MoneyInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsPositiveMoneyQuery implements MoneyQueryInterface
{
    public function queryFrom(MoneyInterface $money)
    {
        return $money->getAmount()->isPositive();
    }
}
