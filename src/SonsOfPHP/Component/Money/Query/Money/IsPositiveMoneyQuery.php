<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsPositiveMoneyQuery implements MoneyQueryInterface
{
    public function queryFrom(MoneyInterface $money): bool
    {
        return $money->getAmount()->isPositive();
    }
}
