<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsGreaterThanOrEqualToMoneyQuery implements MoneyQueryInterface
{
    public function __construct(private readonly MoneyInterface $money) {}

    public function queryFrom(MoneyInterface $money): bool
    {
        if (!$money->getCurrency()->isEqualTo($this->money->getCurrency())) {
            throw new MoneyException('Cannot use different currencies');
        }

        return $money->getAmount()->isGreaterThanOrEqualTo($this->money->getAmount());
    }
}
