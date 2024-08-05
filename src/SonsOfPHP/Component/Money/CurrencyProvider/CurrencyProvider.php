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
    public function getCurrencies(): iterable
    {
        yield Currency::AED(784, 2);
        yield Currency::AFN(971, 2);
        yield Currency::ALL(8, 2);
        yield Currency::AMD(51, 2);
        yield Currency::ANG(532, 2);
        yield Currency::AOA(973, 2);
        yield Currency::ARS(32, 2);
        yield Currency::AUD(36, 2);
        yield Currency::AWG(533, 2);
        yield Currency::AZN(944, 2);

        yield Currency::CAD(124, 2);
        yield Currency::CHF(756, 2);
        yield Currency::CNY(156, 2);
        yield Currency::EUR(978, 2);
        yield Currency::JPY(392, 0);
        yield Currency::RUB(643, 0);
        yield Currency::USD(840, 2);
        yield Currency::USN(997, 2);
    }
}
