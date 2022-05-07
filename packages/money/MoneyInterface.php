<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Query\Money\MoneyQueryInterface;

/**
 * Money Interface
 *
 * Main API for interactiving with Money
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyInterface
{
    /**
     * @return AmountInterface
     */
    public function getAmount(): AmountInterface;

    /**
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface;

    /**
     * @param MoneyOperatorInterface $operator
     *
     * @return MoneyInterface
     */
    public function with(MoneyOperatorInterface $operator): MoneyInterface;

    /**
     * @param MoneyQueryInterface $query
     *
     * @return mixed
     */
    public function query(MoneyQueryInterface $query);

    /**
     * @return bool
     */
    public function isEqualTo(MoneyInterface $money): bool;

    /**
     * Returns true if this is greater than that
     *
     * @return bool
     */
    public function isGreaterThan(MoneyInterface $money): bool;

    /**
     * @return bool
     */
    public function isGreaterThanOrEqualTo(MoneyInterface $money): bool;

    /**
     * @return bool
     */
    public function isLessThan(MoneyInterface $money): bool;

    /**
     * @return bool
     */
    public function isLessThanOrEqualTo(MoneyInterface $money): bool;

    /**
     * @return bool
     */
    public function isNegative(): bool;

    /**
     * @return bool
     */
    public function isPositive(): bool;

    /**
     * @return bool
     */
    public function isZero(): bool;

    /**
     * Adds amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     * @todo MoneyInterface|AmountInterface
     *
     * @return MoneyInterface
     */
    public function add(MoneyInterface $money): MoneyInterface;

    /**
     * Subtracts amount to existing amount and returns a new MoneyInterface
     *
     * @param MoneyInterface $money
     * @todo MoneyInterface|AmountInterface
     *
     * @return MoneyInterface
     */
    public function subtract(MoneyInterface $money): MoneyInterface;

    /**
     * Multiple by multiplier and returns new MoneyInterface
     *
     * @param int|float|string $multiplier
     *
     * @return MoneyInterface
     */
    public function multiply($multiplier): MoneyInterface;

    /**
     * Divide by divisor and returns new MoneyInterface
     *
     * @param int|float|string $divisor
     *
     * @return MoneyInterface
     */
    public function divide($divisor): MoneyInterface;
}
