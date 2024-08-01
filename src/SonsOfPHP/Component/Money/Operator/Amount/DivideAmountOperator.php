<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DivideAmountOperator implements AmountOperatorInterface
{
    public function __construct(private $divisor) {}

    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->getAmount() / $this->divisor);
    }
}
