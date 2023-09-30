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

    public function with(MoneyOperatorInterface $operator): self;

    public function query(MoneyQueryInterface $query);

    public function isEqualTo(self $money): bool;

    /**
     * Returns true if this is greater than that.
     */
    public function isGreaterThan(self $money): bool;

    public function isGreaterThanOrEqualTo(self $money): bool;

    public function isLessThan(self $money): bool;

    public function isLessThanOrEqualTo(self $money): bool;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;

    /**
     * Adds amount to existing amount and returns a new MoneyInterface.
     *
     * @todo MoneyInterface|AmountInterface
     */
    public function add(self $money): self;

    /**
     * Subtracts amount to existing amount and returns a new MoneyInterface.
     *
     * @todo MoneyInterface|AmountInterface
     */
    public function subtract(self $money): self;

    /**
     * Multiple by multiplier and returns new MoneyInterface.
     *
     * @param int|float|string $multiplier
     */
    public function multiply($multiplier): self;

    /**
     * Divide by divisor and returns new MoneyInterface.
     *
     * @param int|float|string $divisor
     */
    public function divide($divisor): self;
}
