<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;
use SonsOfPHP\Contract\Money\Query\Amount\AmountQueryInterface;
use SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery;
use SonsOfPHP\Contract\Money\AmountInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Amount implements AmountInterface
{
    private string $amount;

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

    public function toString(): string
    {
        return $this->amount;
    }

    public function toInt(): int
    {
        return (int) $this->amount;
    }

    public function toFloat(): float
    {
        return (float) $this->amount;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function with(AmountOperatorInterface $operator): AmountInterface
    {
        return $operator->apply($this);
    }

    public function query(AmountQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    public function add(AmountInterface $amount): AmountInterface
    {
        return $this->with(new AddAmountOperator($amount));
    }

    public function subtract(AmountInterface $amount): AmountInterface
    {
        return $this->with(new SubtractAmountOperator($amount));
    }

    public function multiply($multiplier): AmountInterface
    {
        return $this->with(new MultiplyAmountOperator($multiplier));
    }

    public function divide($divisor): AmountInterface
    {
        return $this->with(new DivideAmountOperator($divisor));
    }

    public function isEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsEqualToAmountQuery($amount));
    }

    public function isGreaterThan(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanAmountQuery($amount));
    }

    public function isGreaterThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsGreaterThanOrEqualToAmountQuery($amount));
    }

    public function isLessThan(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanAmountQuery($amount));
    }

    public function isLessThanOrEqualTo(AmountInterface $amount): bool
    {
        return $this->query(new IsLessThanOrEqualToAmountQuery($amount));
    }

    public function isNegative(): bool
    {
        return $this->query(new IsNegativeAmountQuery());
    }

    public function isPositive(): bool
    {
        return $this->query(new IsPositiveAmountQuery());
    }

    public function isZero(): bool
    {
        return $this->query(new IsZeroAmountQuery());
    }
}
