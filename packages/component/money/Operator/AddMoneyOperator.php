<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Money;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AddMoneyOperator implements MoneyOperatorInterface
{
    private MoneyInterface $money;

    public function __construct(MoneyInterface $money)
    {
        $this->money = $money;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(MoneyInterface $money): MoneyInterface
    {
        if (!$this->money->getCurrency()->equals($money->getCurrency())) {
            throw new MoneyException('Cannot add amounts together from different currencies');
        }

        $amount = $money->getAmount() + $this->money->getAmount();

        return new Money($amount, $money->getCurrency());
    }
}
