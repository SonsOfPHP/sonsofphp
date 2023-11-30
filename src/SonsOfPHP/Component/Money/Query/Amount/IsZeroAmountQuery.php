<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsZeroAmountQuery implements AmountQueryInterface
{
    public function queryFrom(AmountInterface $amount)
    {
        return 0 == $amount->getAmount();
    }
}
