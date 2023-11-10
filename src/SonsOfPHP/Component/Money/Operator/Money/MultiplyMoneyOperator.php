<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Money;

use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\Operator\Money\MoneyOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MultiplyMoneyOperator implements MoneyOperatorInterface
{
    private $multiplier;

    public function __construct($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    public function apply(MoneyInterface $money): MoneyInterface
    {
        $amount = $money->getAmount()->multiply($this->multiplier);

        return new Money($amount, $money->getCurrency());
    }
}
