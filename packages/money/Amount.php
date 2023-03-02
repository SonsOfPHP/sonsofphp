<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\AmountOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;
use SonsOfPHP\Component\Money\Query\Amount\AmountQueryInterface;
use SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Amount implements AmountInterface
{
    private string $amount;

    /**
     * @param mixed $amount
     */
    public function __construct($amount)
    {
        $this->amount = (string) $amount;
    }

    /**
     * @see AmountInterface::toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function toInt(): int
    {
        return (int) $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function toFloat(): float
    {
        return (float) $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function with(AmountOperatorInterface $operator): AmountInterface
    {
        return $operator->apply($this);
    }

    /**
     * {@inheritDoc}
     */
    public function query(AmountQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * {@inheritDoc}
     */
    public function add(AmountInterface $amount): AmountInterface
    {
        return $this->with(new AddAmountOperator($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function subtract(AmountInterface $amount): AmountInterface
    {
        return $this->with(new SubtractAmountOperator($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function multiply($multiplier): AmountInterface
    {
        return $this->with(new MultiplyAmountOperator($multiplier));
    }

    /**
     * {@inheritDoc}
     */
    public function divide($divisor): AmountInterface
    {
        return $this->with(new DivideAmountOperator($divisor));
    }

    /**
     * {@inheritDoc}
     */
    public function isEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsEqualToAmountQuery($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function isGreaterThan(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanAmountQuery($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function isGreaterThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanOrEqualToAmountQuery($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function isLessThan(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanAmountQuery($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function isLessThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanOrEqualToAmountQuery($amount));
    }

    /**
     * {@inheritDoc}
     */
    public function isNegative(): bool
    {
        return $this->query(new IsNegativeAmountQuery());
    }

    /**
     * {@inheritDoc}
     */
    public function isPositive(): bool
    {
        return $this->query(new IsPositiveAmountQuery());
    }

    /**
     * {@inheritDoc}
     */
    public function isZero(): bool
    {
        return $this->query(new IsZeroAmountQuery());
    }
}
