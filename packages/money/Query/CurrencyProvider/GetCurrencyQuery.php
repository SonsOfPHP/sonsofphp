<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\CurrencyProvider;

use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProviderInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class GetCurrencyQuery implements CurrencyProviderQueryInterface
{
    private CurrencyInterface $currency;

    public function __construct($currency)
    {
        if ($currency instanceof CurrencyInterface) {
            $this->currency = $currency;

            return;
        }

        if (is_string($currency) && 3 === strlen($currency)) {
            $this->currency = new Currency($currency);

            return;
        }

        throw new MoneyException('Value Error');
    }

    /**
     * {@inheritdoc}
     */
    public function queryFrom(CurrencyProviderInterface $provider)
    {
        foreach ($provider->getCurrencies() as $currency) {
            if ($currency->isEqualTo($this->currency)) {
                return $currency;
            }
        }

        throw new MoneyException('Currency does not exist in this provider');
    }
}
