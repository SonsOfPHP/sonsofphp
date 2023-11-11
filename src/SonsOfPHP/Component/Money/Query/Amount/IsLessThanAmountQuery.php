<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Query\Amount\AmountQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsLessThanAmountQuery implements AmountQueryInterface
{
    private AmountInterface $amount;

    public function __construct(AmountInterface $amount)
    {
        $this->amount = $amount;
    }

    public function queryFrom(AmountInterface $amount)
    {
        return $amount->getAmount() < $this->amount->getAmount();
    }
}
