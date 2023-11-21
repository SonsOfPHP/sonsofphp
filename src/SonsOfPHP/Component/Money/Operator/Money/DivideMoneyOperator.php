<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Money;

use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyInterface;
use SonsOfPHP\Contract\Money\MoneyOperatorInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DivideMoneyOperator implements MoneyOperatorInterface
{
    private $divisor;

    public function __construct($divisor)
    {
        $this->divisor = $divisor;
    }

    public function apply(MoneyInterface $money): MoneyInterface
    {
        $amount = $money->getAmount()->divide($this->divisor);

        return new Money($amount, $money->getCurrency());
    }
}
