<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Money;

use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyOperatorInterface
{
    /**
     * @param MoneyInterface $money
     *
     * @throws MoneyException
     *
     * @return MoneyInterface
     */
    public function apply(MoneyInterface $money): MoneyInterface;
}
