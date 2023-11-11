<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\CurrencyProviderInterface;
use SonsOfPHP\Contract\Money\Query\CurrencyProvider\CurrencyProviderQueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCurrencyProvider implements CurrencyProviderInterface
{
    public function query(CurrencyProviderQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    public function hasCurrency($currency): bool
    {
        return $this->query(new HasCurrencyQuery($currency));
    }

    public function getCurrency($currency): CurrencyInterface
    {
        return $this->query(new GetCurrencyQuery($currency));
    }
}
