<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Query\Currency\CurrencyQueryInterface;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Currency implements CurrencyInterface
{
    private string $currencyCode;
    private ?int $numericCode;
    private ?int $minorUnit;

    /**
     * @param int $numericCode
     * @param int $minorUnit
     */
    public function __construct(string $currencyCode, ?int $numericCode = null, ?int $minorUnit = null)
    {
        $this->currencyCode = strtoupper($currencyCode);
        $this->numericCode = $numericCode;
        $this->minorUnit = $minorUnit;
    }

    /**
     * @see self::getCurrencyCode()
     */
    public function __toString(): string
    {
        return $this->getCurrencyCode();
    }

    /**
     * Makes it easy to create new currencies.
     *
     * Examples:
     *   Currency::USD();
     *   Currency::USD(840, 2);
     */
    public static function __callStatic(string $currencyCode, array $args): CurrencyInterface
    {
        $numericCode = $args[0] ?? null;
        $minorUnit = $args[1] ?? null;

        return new static($currencyCode, $numericCode, $minorUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function query(CurrencyQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumericCode(): ?int
    {
        return $this->numericCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinorUnit(): ?int
    {
        return $this->minorUnit;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(CurrencyInterface $currency): bool
    {
        return $this->query(new IsEqualToCurrencyQuery($currency));
    }
}
