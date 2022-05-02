<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsGreaterThanOrEqualToMoneyQuery implements MoneyQueryInterface
{
    private MoneyInterface $money;

    public function __construct(MoneyInterface $money)
    {
        $this->money = $money;
    }

    /**
     * {@inheritdoc}
     */
    public function queryFrom(MoneyInterface $money)
    {
        if (!$this->money->getCurrency()->equals($money->getCurrency())) {
            throw new MoneyException('Cannot use different currencies');
        }

        return $money->getAmount() >= $this->money->getAmount();
    }
}
