<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\CurrencyProviderQueryInterface;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCurrencyProvider implements CurrencyProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function query(CurrencyProviderQueryInterface $query)
    {
        return $query->queryFrom($this);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCurrency($currency): bool
    {
        return $this->query(new HasCurrencyQuery($currency));
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency($currency): CurrencyInterface
    {
        return $this->query(new GetCurrencyQuery($currency));
    }
}
