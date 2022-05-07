<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Component\Money\AmountInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsGreaterThanAmountQuery implements AmountQueryInterface
{
    private AmountInterface $amount;

    public function __construct(AmountInterface $amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function queryFrom(AmountInterface $amount)
    {
        return $amount->getAmount() > $this->amount->getAmount();
    }
}
