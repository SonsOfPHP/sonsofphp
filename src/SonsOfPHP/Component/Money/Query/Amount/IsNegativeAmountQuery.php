<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Query\Amount\AmountQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsNegativeAmountQuery implements AmountQueryInterface
{
    public function queryFrom(AmountInterface $amount)
    {
        return $amount->getAmount() < 0;
    }
}
