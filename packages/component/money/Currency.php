<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

//use SonsOfPHP\Component\Money\Operator\CurrencyOperatorInterface;
use SonsOfPHP\Component\Money\Query\CurrencyQueryInterface;
use SonsOfPHP\Component\Money\Query\IsEqualToCurrencyQuery;

/**
 * Currency
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Currency implements CurrencyInterface
{
    private string $currencyCode;
    private ?int $numericCode;
    private ?int $minorUnit;

    /**
     * @param string $currencyCode
     */
    public function __construct(string $currencyCode, ?int $numericCode = null, ?int $minorUnit = null)
    {
        $this->currencyCode = strtoupper($currencyCode);
        $this->numericCode  = $numericCode;
        $this->minorUnit    = $minorUnit;
    }

    /**
     * @see self::getCurrencyCode()
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCurrencyCode();
    }

    /**
     * Example: Currency::USD();
     */
    public static function __callStatic(string $currencyCode, array $args)
    {
        $numericCode = isset($args[0]) ? $args[0] : null;
        $minorUnit   = isset($args[1]) ? $args[1] : null;

        return new static($currencyCode, $numericCode, $minorUnit);
    }

    //public function with(CurrencyOperatorInterface $operator): CurrencyInterface
    //{
    //    return $operator->apply($this);
    //}

    /**
     * {@inheritdoc}
     */
    public function query(CurrencyQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * Returns the Currency Code for this object
     *
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getNumericCode(): ?int
    {
        return $this->numericCode;
    }

    public function getMinorUnit(): ?int
    {
        return $this->minorUnit;
    }

    /**
     * Compare two currencies to see if they are the same
     *
     * @param CurrencyInterface $currency
     *
     * @return bool
     */
    public function equals(CurrencyInterface $currency): bool
    {
        return $this->query(new IsEqualToCurrencyQuery($currency));
    }
}
