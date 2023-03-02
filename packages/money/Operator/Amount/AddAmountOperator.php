<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\AmountInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AddAmountOperator implements AmountOperatorInterface
{
    private AmountInterface $amount;

    public function __construct(AmountInterface $amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->toFloat() + $this->amount->toFloat());
    }
}
