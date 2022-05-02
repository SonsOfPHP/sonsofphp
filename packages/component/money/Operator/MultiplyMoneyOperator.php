<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Money;

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

    /**
     * {@inheritdoc}
     */
    public function apply(MoneyInterface $money): MoneyInterface
    {
        $amount = $money->getAmount() * $this->multiplier;

        return new Money($amount, $money->getCurrency());
    }
}
