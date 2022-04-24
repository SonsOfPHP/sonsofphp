<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

/**
 * Money
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Money implements MoneyInterface
{
    private $value;
    private CurrencyInterface $currency;

    /**
     */
    public function __construct($value, CurrencyInterface $currency)
    {
        $this->value    = $value;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value.' '.$this->currency->getCurrencyCode();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     */
    public function equals(MoneyInterface $that): bool
    {
        return $this->getValue() === $that->getValue() && $this->getCurrency()->equals($that->getCurrency());
    }
}
