<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Operator\Amount;

use SonsOfPHP\Component\Money\AmountInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountOperatorInterface
{
    /**
     * @throws MoneyException
     */
    public function apply(AmountInterface $amount): AmountInterface;
}
