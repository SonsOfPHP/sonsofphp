<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Currency;

use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IsEqualToCurrencyQuery implements CurrencyQueryInterface
{
    private CurrencyInterface $currency;

    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    /**
     * {@inheritdoc}
     */
    public function queryFrom(CurrencyInterface $currency)
    {
        return $currency->getCurrencyCode() === $this->currency->getCurrencyCode();
    }
}
