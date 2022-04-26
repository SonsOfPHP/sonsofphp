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
    private $amount;
    private CurrencyInterface $currency;

    /**
     */
    public function __construct($amount, CurrencyInterface $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->amount;
    }

    /**
     * Example: Money::USD(100);
     */
    public static function __callStatic(string $method, array $args)
    {
        return new static($args[0], new Currency($method));
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     * @return bool
     */
    public function equals(MoneyInterface $that): bool
    {
        return $this->getAmount() === $that->getAmount() && $this->getCurrency()->equals($that->getCurrency());
    }

    /**
     * @param MoneyInterface $that
     *
     * @return int
     *  -1 = this less than that
     *   0 = this equals that
     *   1 = this greater than that
     */
    public function compare(MoneyInterface $that): int
    {
        if ($this->getCurrency()->getCode() !== $that->getCurrency()->getCode()) {
            throw new MoneyException('Cannot Compare different curriences');
        }

        if ($this->getAmount() < $that->getAmount()) {
            return -1;
        }

        if ($this->getAmount() > $that->getAmount()) {
            return 1;
        }

        return 0;
    }

    /**
     * @todo
     * Adds amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     *
     * @return static
     */
    public function add(MoneyInterface $money)
    {
    }

    /**
     * @todo
     * Subtracts amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     *
     * @return static
     */
    public function subtract(MoneyInterface $money)
    {
    }

    /**
     * @todo
     * Multiple by multiplier and returns new MoneyInterface
     *
     * @param int|string
     *
     * @return static
     */
    public function multiply($multiplier)
    {
    }

    /**
     * @todo
     * Divide by divisor and returns new MoneyInterface
     *
     * @param int|string
     *
     * @return static
     */
    public function divide($divisor)
    {
    }
}
