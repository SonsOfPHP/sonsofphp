<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\AmountInterface;
use SonsOfPHP\Component\Money\Amount;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SubtractAmountOperator implements AmountOperatorInterface
{
    private AmountInterface $amount;

    public function __construct(AmountInterface $amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->toFloat() - $this->amount->toFloat());
    }
}
