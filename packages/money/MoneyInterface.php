<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Query\Money\MoneyQueryInterface;

/**
 * Money Interface.
 *
 * Main API for interactiving with Money
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyInterface
{
    public function getAmount(): AmountInterface;

    public function getCurrency(): CurrencyInterface;

    public function with(MoneyOperatorInterface $operator): MoneyInterface;

    /**
     * @return mixed
     */
    public function query(MoneyQueryInterface $query);

    public function isEqualTo(MoneyInterface $money): bool;

    /**
     * Returns true if this is greater than that.
     */
    public function isGreaterThan(MoneyInterface $money): bool;

    public function isGreaterThanOrEqualTo(MoneyInterface $money): bool;

    public function isLessThan(MoneyInterface $money): bool;

    public function isLessThanOrEqualTo(MoneyInterface $money): bool;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;

    /**
     * Adds amount to existing amount and returns a new MoneyInterface.
     *
     * @todo MoneyInterface|AmountInterface
     */
    public function add(MoneyInterface $money): MoneyInterface;

    /**
     * Subtracts amount to existing amount and returns a new MoneyInterface.
     *
     * @todo MoneyInterface|AmountInterface
     */
    public function subtract(MoneyInterface $money): MoneyInterface;

    /**
     * Multiple by multiplier and returns new MoneyInterface.
     *
     * @param int|float|string $multiplier
     */
    public function multiply($multiplier): MoneyInterface;

    /**
     * Divide by divisor and returns new MoneyInterface.
     *
     * @param int|float|string $divisor
     */
    public function divide($divisor): MoneyInterface;
}
