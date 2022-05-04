<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsZeroMoneyQuery implements MoneyQueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function queryFrom(MoneyInterface $money)
    {
        return $money->getAmount() == 0;
    }
}
