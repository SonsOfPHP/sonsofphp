<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money\Operator\Amount;

use SonsOfPHP\Contract\Money\AmountInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountOperatorInterface
{
    /**
     * @throws MoneyExceptionInterface
     */
    public function apply(AmountInterface $amount): AmountInterface;
}