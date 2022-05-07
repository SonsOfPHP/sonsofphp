<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Exception\UnknownCurrencyException;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\CurrencyProviderQueryInterface;

/**
 * Currency Provider
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyProviderInterface
{
    /**
     * Returns all of the currencies this Currency Provide will provide
     *
     * @throw MoneyException This method may throw an exception if, for example it's making queries
     *                       to a database, if it's unable to return any currencies
     *
     * @return CurrencyInterface[]
     */
    public function getCurrencies(): iterable;

    /**
     * Pass in a currency object or a currency code (ie USD) and it
     * will return true is the currency exists in this provider
     *
     * @param CurrencyInterface|string $currency
     *
     * @throw MoneyException
     * @throw UnknownCurrencyException
     *
     * @return bool
     */
    public function hasCurrency($currency): bool;

    /**
     * Returns the currency or thows MoneyException is currency does not
     * exist in this provider
     *
     * @param CurrencyInterface|string $currency
     *
     * @throw MoneyException
     * @throw UnknownCurrencyException
     *
     * @return CurrencyInterface
     */
    public function getCurrency($currency): CurrencyInterface;

    /**
     * In case you need to run your own queries against this provider the query
     * method will allow you to do this.
     *
     * @param CurrencyProviderQueryInterface $query
     *
     * @throw MoneyException
     *
     * @return mixed
     */
    public function query(CurrencyProviderQueryInterface $query);
}
