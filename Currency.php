<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

/**
 * Currency
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Currency implements CurrencyInterface
{
    private string $currencyCode;

    /**
     * @param string $currencyCode
     */
    public function __construct(string $currencyCode)
    {
        $this->currencyCode = strtoupper($currencyCode);
    }

    /**
     * @see self::getCurrencyCode()
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCurrencyCode();
    }

    /**
     * Returns the Currency Code for this object
     *
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * Compare two currencies to see if they are the same
     *
     * @param CurrencyInterface $that
     *
     * @return bool
     */
    public function equals(CurrencyInterface $that): bool
    {
        return $this->getCurrencyCode() === $that->getCurrencyCode();
    }
}
