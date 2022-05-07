<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\CurrencyProvider;

use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProviderInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Query\QueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyProviderQueryInterface extends QueryInterface
{
    /**
     * @param CurrencyProviderInterface $provider
     *
     * @throws MoneyException
     *
     * @return mixed
     */
    public function queryFrom(CurrencyProviderInterface $provider);
}
