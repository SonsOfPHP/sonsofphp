<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Component\Money\Currency;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class XCurrencyProvider extends AbstractCurrencyProvider
{
    /**
     * {@inheritDoc}
     */
    public function getCurrencies(): iterable
    {
        yield Currency::XAF(950, 0);
        yield Currency::XAG(961, 0);
        yield Currency::XAU(959, 0);
        yield Currency::XBA(955, 0);
        yield Currency::XBB(956, 0);
        yield Currency::XBC(957, 0);
        yield Currency::XBD(958, 0);
        yield Currency::XCD(951, 2);
        yield Currency::XDR(960, 0);
        yield Currency::XOF(952, 0);
        yield Currency::XPD(964, 0);
        yield Currency::XPF(953, 0);
        yield Currency::XPT(962, 0);
        yield Currency::XSU(994, 0);
        yield Currency::XTS(963, 0); // Code Reserved for testing
        yield Currency::XUA(965, 0);
        yield Currency::XXX(999, 0); // No Currency
    }
}
