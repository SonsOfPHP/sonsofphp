<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Currency;

use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\CurrencyQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsEqualToCurrencyQuery implements CurrencyQueryInterface
{
    public function __construct(private readonly CurrencyInterface $currency) {}

    public function queryFrom(CurrencyInterface $currency): bool
    {
        return $currency->getCurrencyCode() === $this->currency->getCurrencyCode();
    }
}
