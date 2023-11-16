<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyParserInterface
{
    /**
     * Examples
     *   parse('$4.20')
     *   parse('4.20', 'usd')
     *   parse('4.20', Currency::USD())
     */
    public function parse(strin $money, Currency|string|null $currency = null): string;
}
