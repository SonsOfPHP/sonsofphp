<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DivideAmountOperator implements AmountOperatorInterface
{
    private $divisor;

    public function __construct($divisor)
    {
        $this->divisor = $divisor;
    }

    public function apply(AmountInterface $amount): AmountInterface
    {
        return new Amount($amount->getAmount() / $this->divisor);
    }
}
