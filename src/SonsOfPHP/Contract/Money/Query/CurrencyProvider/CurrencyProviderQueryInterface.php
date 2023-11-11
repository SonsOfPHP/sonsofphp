<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money\Query\CurrencyProvider;

use SonsOfPHP\Contract\Money\CurrencyProviderInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyProviderQueryInterface
{
    /**
     * @throws MoneyExceptionInterface
     */
    public function queryFrom(CurrencyProviderInterface $provider);
}
