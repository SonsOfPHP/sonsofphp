<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money;

/**
 * Amount.
 *
 * The amount is used to represent the Numerical Value of the Money.
 *
 * The amount SHOULD be represented in the smallest form of the currency. So
 * for USD a value of `420` would represent `$4.20`.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountInterface // extends \Stringable
{
    /**
     * Allows you to run you own operations of the amount.
     */
    public function with(AmountOperatorInterface $operator): static;

    /**
     * Allows you to ask different questions about the amount and get
     * different results returned to you.
     */
    public function query(AmountQueryInterface $query)/*: mixed*/;

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
    //public function toFloat(): float;

    /**
     * Returns the value that this is for the object.
     */
    public function getAmount(): string;

    /**
     * Add amounts together.
     */
    public function add(AmountInterface $amount): static;

    /**
     * Subtract amounts from each other.
     *
     * Example:
     *   $amount1 = 100
     *   $amount2 = 50
     *   $amount1->subtract($amount2) == 50
     *   $amount2->subtract($amount1) == -50
     */
    public function subtract(AmountInterface $amount): static;

    /**
     * Multiply amount by a specific amount.
     */
    public function multiply(/*int */$multiplier): static;

    /**
     * Divide the amount by a specific amount.
     */
    public function divide(/*int */$divisor): static;

    public function isEqualTo(AmountInterface $amount): bool;

    public function isGreaterThan(AmountInterface $amount): bool;

    public function isGreaterThanOrEqualTo(AmountInterface $amount): bool;

    public function isLessThan(AmountInterface $amount): bool;

    public function isLessThanOrEqualTo(AmountInterface $amount): bool;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;
}
