<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Query\Currency\CurrencyQueryInterface;

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
     * Defined by the ISO 4217 standard
     *
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * The Currency's Numeric Code
     *
     * Defined by the ISO 4217 standard
     *
     * @return int|null This may return null if the either does not have one or is unknown
     */
    public function getNumericCode(): ?int;

    /**
     * The Currency's Minor Unit
     *
     * Defined by the ISO 4217 standard
     *
     * “0” means that there is no minor unit for that currency, whereas “1”,
     * “2” and “3” signify a ratio of 10:1, 100:1 and 1000:1 respectively.
     *
     * @return int|null This may return null if the either does not have one or is unknown
     */
    public function getMinorUnit(): ?int;

    /**
     * Compare two currencies to see if they are the same
     *
     * @param CurrencyInterface $currency
     *
     * @return bool
     */
    public function isEqualTo(CurrencyInterface $currency): bool;

    /**
     * @param CurrencyQueryInterface $query
     *
     * @return mixed
     */
    public function query(CurrencyQueryInterface $query);
}
