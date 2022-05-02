<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\CurrencyProviderInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyProviderQueryInterface extends QueryInterface
{
    /**
     * @param CurrecnyProviderInterface $provider
     *
     * @throws MoneyException
     *
     * @return mixed
     */
    public function queryFrom(CurrencyProviderInterface $provider);
}
