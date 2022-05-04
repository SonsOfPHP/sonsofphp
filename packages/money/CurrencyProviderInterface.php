<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

/**
 * Currency Provider
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyProviderInterface
{
    public function getCurrencies(): iterable;
}
