<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Query\CurrencyProviderQueryInterface;
use SonsOfPHP\Component\Money\Query\GetCurrencyQuery;
use SonsOfPHP\Component\Money\Query\HasCurrencyQuery;

/**
 * Currency Provider
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CurrencyProvider implements CurrencyProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCurrencies(): iterable
    {
        yield Currency::USD(840, 2);
    }

    /**
     * @param CurrencyProviderQueryInterface $query
     *
     * @throw MoneyException
     *
     * @return mixed
     */
    public function query(CurrencyProviderQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * Pass in a currency object or a currency code (ie USD) and it
     * will return true is the currency exists in this provider
     *
     * @param CurrencyInterface|string $currency
     *
     * @throw MoneyException
     *
     * @return bool
     */
    public function hasCurrency($currency): bool
    {
        return $this->query(new HasCurrencyQuery($currency));
    }

    /**
     * Returns the currency or thows MoneyException is currency does not
     * exist in this provider
     *
     * @param CurrencyInterface|string $currency
     *
     * @throw MoneyException
     *
     * @return CurrencyInterface
     */
    public function getCurrency($currency): CurrencyInterface
    {
        return $this->query(new GetCurrencyQuery($currency));
    }
}
