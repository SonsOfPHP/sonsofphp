<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MultiplyAmountOperator implements AmountOperatorInterface
{
    private $multiplier;

    public function __construct($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->getAmount() * $this->multiplier);
    }
}
