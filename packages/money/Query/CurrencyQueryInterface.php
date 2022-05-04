<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CurrencyQueryInterface extends QueryInterface
{
    /**
     * @param CurrecnyInterface $currency
     *
     * @throws MoneyException
     *
     * @return mixed
     */
    public function queryFrom(CurrencyInterface $currency);
}
