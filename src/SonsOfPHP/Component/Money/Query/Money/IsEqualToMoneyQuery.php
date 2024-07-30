<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsEqualToMoneyQuery implements MoneyQueryInterface
{
    public function __construct(private readonly MoneyInterface $money) {}

    public function queryFrom(MoneyInterface $money): bool
    {
        return $this->money->getAmount()->isEqualTo($money->getAmount()) && $this->money->getCurrency()->isEqualTo($money->getCurrency());
    }
}
