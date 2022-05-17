<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Component\Money\AmountInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsZeroAmountQuery implements AmountQueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function queryFrom(AmountInterface $amount)
    {
        return 0 == $amount->getAmount();
    }
}
