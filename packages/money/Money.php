<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator;
use SonsOfPHP\Component\Money\Query\Money\MoneyQueryInterface;
use SonsOfPHP\Component\Money\Query\Money\IsEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsNegativeMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsPositiveMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Money implements MoneyInterface
{
    private AmountInterface $amount;
    private CurrencyInterface $currency;

    /**
     * @param mixed             $amount
     * @param CurrencyInterface $currency
     */
    public function __construct($amount, CurrencyInterface $currency)
    {
        if (!$amount instanceof AmountInterface) {
            $amount = new Amount($amount);
        }

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
     * @param MoneyInterface $that
     *
     * @return int
     *  -1 = this less than that
     *   0 = this equals that
     *   1 = this greater than that
     */
    public function compare(MoneyInterface $money): int
    {
        if ($this->isLessThan($money)) {
            return -1;
        }

        if ($this->isGreaterThan($money)) {
            return 1;
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function with(MoneyOperatorInterface $operator): MoneyInterface
    {
        return $operator->apply($this);
    }

    /**
     * {@inheritdoc}
     */
    public function query(MoneyQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): AmountInterface
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(MoneyInterface $money): bool
    {
        return $this->query(new IsEqualToMoneyQuery($money));
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThan(MoneyInterface $money): bool
    {
        return $this->query(new IsGreaterThanMoneyQuery($money));
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThanOrEqualTo(MoneyInterface $money): bool
    {
        return $this->query(new IsGreaterThanOrEqualToMoneyQuery($money));
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThan(MoneyInterface $money): bool
    {
        return $this->query(new IsLessThanMoneyQuery($money));
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThanOrEqualTo(MoneyInterface $money): bool
    {
        return $this->query(new IsLessThanOrEqualToMoneyQuery($money));
    }

    /**
     * {@inheritdoc}
     */
    public function isNegative(): bool
    {
        return $this->query(new IsNegativeMoneyQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function isPositive(): bool
    {
        return $this->query(new IsPositiveMoneyQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function isZero(): bool
    {
        return $this->query(new IsZeroMoneyQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function add(MoneyInterface $money): MoneyInterface
    {
        return $this->with(new AddMoneyOperator($money));
    }

    /**
     * {@inheritdoc}
     */
    public function subtract(MoneyInterface $money): MoneyInterface
    {
        return $this->with(new SubtractMoneyOperator($money));
    }

    /**
     * {@inheritdoc}
     */
    public function multiply($multiplier): MoneyInterface
    {
        return $this->with(new MultiplyMoneyOperator($multiplier));
    }

    /**
     * {@inheritdoc}
     */
    public function divide($divisor): MoneyInterface
    {
        return $this->with(new DivideMoneyOperator($divisor));
    }
}
