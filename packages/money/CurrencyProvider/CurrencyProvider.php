<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Component\Money\Currency;

/**
 * Currency Provider.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CurrencyProvider extends AbstractCurrencyProvider
{
    /**
     * {@inheritDoc}
     */
    public function getCurrencies(): iterable
    {
        // @todo Add all the currencies
        yield Currency::USD(840, 2);
    }
}
