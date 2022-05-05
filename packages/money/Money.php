<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Operator\AddMoneyOperator;
use SonsOfPHP\Component\Money\Operator\SubtractMoneyOperator;
use SonsOfPHP\Component\Money\Operator\MultiplyMoneyOperator;
use SonsOfPHP\Component\Money\Operator\DivideMoneyOperator;
use SonsOfPHP\Component\Money\Query\MoneyQueryInterface;
use SonsOfPHP\Component\Money\Query\IsGreaterThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsGreaterThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsLessThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsLessThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsNegativeMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsPositiveMoneyQuery;
use SonsOfPHP\Component\Money\Query\IsZeroMoneyQuery;

/**
 * Money
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Money implements MoneyInterface
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
     * @param MoneyOperatorInterface $operator
     *
     * @return static
     */
    public function with(MoneyOperatorInterface $operator): MoneyInterface
    {
        return $operator->apply($this);
    }

    /**
     * @param MoneyQueryInterface $query
     *
     * @return mixed
     */
    public function query(MoneyQueryInterface $query)
    {
        return $query->queryFrom($this);
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
    public function equals(MoneyInterface $money): bool
    {
        return $this->query(new IsEqualToMoneyQuery($money));
    }

    /**
     * Returns true if this is greater than that
     *
     * @return bool
     */
    public function greaterThan(MoneyInterface $money): bool
    {
        return $this->query(new IsGreaterThanMoneyQuery($money));
    }

    /**
     * @return bool
     */
    public function greaterThanOrEquals(MoneyInterface $money): bool
    {
        return $this->query(new IsGreaterThanOrEqualToMoneyQuery($money));
    }

    /**
     * @return bool
     */
    public function lessThan(MoneyInterface $money): bool
    {
        return $this->query(new IsLessThanMoneyQuery($money));
    }

    /**
     * @return bool
     */
    public function lessThanOrEquals(MoneyInterface $money): bool
    {
        return $this->query(new IsLessThanOrEqualToMoneyQuery($money));
    }

    /**
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->query(new IsNegativeMoneyQuery());
    }

    /**
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->query(new IsPositiveMoneyQuery());
    }

    /**
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->query(new IsZeroMoneyQuery());
    }

    /**
     * @param MoneyInterface $that
     *
     * @return int
     *  -1 = this less than that
     *   0 = this equals that
     *   1 = this greater than that
     */
    public function compare(MoneyInterface $money): int
    {
        if ($this->lessThan($money)) {
            return -1;
        }

        if ($this->greaterThan($money)) {
            return 1;
        }

        return 0;
    }

    /**
     * Adds amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     *
     * @return static
     */
    public function add(MoneyInterface $money): MoneyInterface
    {
        return $this->with(new AddMoneyOperator($money));
    }

    /**
     * Subtracts amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     *
     * @return static
     */
    public function subtract(MoneyInterface $money)
    {
        return $this->with(new SubtractMoneyOperator($money));
    }

    /**
     * Multiple by multiplier and returns new MoneyInterface
     *
     * @param int|float|string $multiplier
     *
     * @return static
     */
    public function multiply($multiplier)
    {
        return $this->with(new MultiplyMoneyOperator($multiplier));
    }

    /**
     * Divide by divisor and returns new MoneyInterface
     *
     * @param int|float|string $divisor
     *
     * @return static
     */
    public function divide($divisor)
    {
        return $this->with(new DivideMoneyOperator($divisor));
    }
}
