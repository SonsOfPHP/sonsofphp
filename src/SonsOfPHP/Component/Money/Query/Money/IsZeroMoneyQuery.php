<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\Query\Money\MoneyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsZeroMoneyQuery implements MoneyQueryInterface
{
    public function queryFrom(MoneyInterface $money)
    {
        return $money->getAmount()->isZero();
    }
}
