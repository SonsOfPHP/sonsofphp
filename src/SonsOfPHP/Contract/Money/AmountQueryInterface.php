<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money;

use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountQueryInterface
{
    /**
     * @throws MoneyExceptionInterface
     */
    public function queryFrom(AmountInterface $amount);
}
