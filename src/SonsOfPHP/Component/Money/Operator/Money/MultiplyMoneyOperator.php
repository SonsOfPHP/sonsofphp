<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Money;

use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MultiplyMoneyOperator implements MoneyOperatorInterface
{
    public function __construct(private $multiplier) {}

    public function apply(MoneyInterface $money): MoneyInterface
    {
        $amount = $money->getAmount()->multiply($this->multiplier);

        return new Money($amount, $money->getCurrency());
    }
}
