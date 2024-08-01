<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsPositiveAmountQuery implements AmountQueryInterface
{
    public function queryFrom(AmountInterface $amount): bool
    {
        return $amount->getAmount() > 0;
    }
}
