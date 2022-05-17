<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Money;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\MoneyInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyOperatorInterface
{
    /**
     * @throws MoneyException
     */
    public function apply(MoneyInterface $money): MoneyInterface;
}
