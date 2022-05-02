<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

/**
 * Currency Interface
 *
 * @see https://en.wikipedia.org/wiki/ISO_4217
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyInterface
{
    /**
     * Returns the Alphabetic Code of the currency
     *
     * ie USD
     *
     * @return string
     */
    //public function getCurrencyCode(): string;

    // ie 840
    //public function getNumericCode(): int;

    // ie 2
    //public function getMinorUnit(): int;

    /**
     * Returns true if the currencies are the same
     *
     * @return bool
     */
    //public function equals(CurrencyInterface $currency): bool;
}
