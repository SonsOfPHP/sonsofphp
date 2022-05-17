<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\AmountInterface;

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

    /**
     * {@inheritdoc}
     */
    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->getAmount() * $this->multiplier);
    }
}
