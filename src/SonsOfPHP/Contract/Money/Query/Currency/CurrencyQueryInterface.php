<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money\Query\Currency;

use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyQueryInterface
{
    /**
     * @throws MoneyExceptionInterface
     */
    public function queryFrom(CurrencyInterface $currency);
}
