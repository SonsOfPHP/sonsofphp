<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsGreaterThanAmountQuery implements AmountQueryInterface
{
    public function __construct(private readonly AmountInterface $amount) {}

    public function queryFrom(AmountInterface $amount): bool
    {
        return $amount->getAmount() > $this->amount->getAmount();
    }
}
