<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;

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

    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->toFloat() - $this->amount->toFloat());
    }
}
