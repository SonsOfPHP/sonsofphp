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
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function toInt(): int
    {
        return (int) $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function toFloat(): float
    {
        return (float) $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function with(AmountOperatorInterface $operator): AmountInterface
    {
        return $operator->apply($this);
    }

    /**
     * {@inheritdoc}
     */
    public function query(AmountQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * {@inheritdoc}
     */
    public function add(AmountInterface $amount): AmountInterface
    {
        return $this->with(new AddAmountOperator($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function subtract(AmountInterface $amount): AmountInterface
    {
        return $this->with(new SubtractAmountOperator($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function multiply($multiplier): AmountInterface
    {
        return $this->with(new MultiplyAmountOperator($multiplier));
    }

    /**
     * {@inheritdoc}
     */
    public function divide($divisor): AmountInterface
    {
        return $this->with(new DivideAmountOperator($divisor));
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsEqualToAmountQuery($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThan(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanAmountQuery($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanOrEqualToAmountQuery($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThan(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanAmountQuery($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanOrEqualToAmountQuery($amount));
    }

    /**
     * {@inheritdoc}
     */
    public function isNegative(): bool
    {
        return $this->query(new IsNegativeAmountQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function isPositive(): bool
    {
        return $this->query(new IsPositiveAmountQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function isZero(): bool
    {
        return $this->query(new IsZeroAmountQuery());
    }
}
