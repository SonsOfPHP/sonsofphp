<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HasCurrencyQuery implements CurrencyProviderQueryInterface
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
            if ($currency->equals($this->currency)) {
                return true;
            }
        }

        return false;
    }
}
