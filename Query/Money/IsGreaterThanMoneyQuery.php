<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\MoneyInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsGreaterThanMoneyQuery implements MoneyQueryInterface
{
    private MoneyInterface $money;

    public function __construct(MoneyInterface $money)
    {
        $this->money = $money;
    }

    public function queryFrom(MoneyInterface $money)
    {
        if (!$money->getCurrency()->isEqualTo($this->money->getCurrency())) {
            throw new MoneyException('Cannot use different currencies');
        }

        return $money->getAmount()->isGreaterThan($this->money->getAmount());
    }
}
