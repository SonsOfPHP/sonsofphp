<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Amount\AmountOperatorInterface;
use SonsOfPHP\Component\Money\Query\Amount\AmountQueryInterface;

/**
 * Amount.
 *
 * The amount is used to represent the Numerical Value of the Money.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountInterface
{
    /**
     * Considering some of these methods.
     */
    // public function getPrecision(): int;
    // public function getScale(): int;

    /**
     * Allows you to run you own operations of the amount.
     */
    public function with(AmountOperatorInterface $operator): AmountInterface;

    /**
     * Allows you to ask different questions about the amount and get
     * different results returned to you.
     *
     * @return mixed
     */
    public function query(AmountQueryInterface $query);

    /**
     * Returns the value for this amount as a string.
     */
    public function toString(): string;

    /**
     * Returns the value for this amount as a integer.
     */
    public function toInt(): int;

    /**
     * Returns the value for this amount as a float.
     */
    public function toFloat(): float;

    /**
     * Returns the value that this is for the object.
     */
    public function getAmount(): string;

    /**
     * Add amounts together.
     */
    public function add(AmountInterface $amount): AmountInterface;

    /**
     * Subtract amounts from each other.
     *
     * Example:
     *   $amount1 = 100
     *   $amount2 = 50
     *   $amount1->subtract($amount2) == 50
     *   $amount2->subtract($amount1) == -50
     */
    public function subtract(AmountInterface $amount): AmountInterface;

    /**
     * Multiply amount by a specific amount.
     *
     * @param mixed $multiplier
     */
    public function multiply($multiplier): AmountInterface;

    /**
     * Divide the amount by a specific amount.
     *
     * @param mixed $divisor
     */
    public function divide($divisor): AmountInterface;

    public function isEqualTo(AmountInterface $amount): bool;

    public function isGreaterThan(AmountInterface $amount): bool;

    public function isGreaterThanOrEqualTo(AmountInterface $amount): bool;

    public function isLessThan(AmountInterface $amount): bool;

    public function isLessThanOrEqualTo(AmountInterface $amount): bool;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;
}
