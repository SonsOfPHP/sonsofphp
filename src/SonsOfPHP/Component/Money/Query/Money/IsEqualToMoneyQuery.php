<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Component\Money\MoneyInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsEqualToMoneyQuery implements MoneyQueryInterface
{
    private MoneyInterface $money;

    public function __construct(MoneyInterface $money)
    {
        $this->money = $money;
    }

    public function queryFrom(MoneyInterface $money)
    {
        return $this->money->getAmount()->isEqualTo($money->getAmount()) && $this->money->getCurrency()->isEqualTo($money->getCurrency());
    }
}
