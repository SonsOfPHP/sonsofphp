<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyQueryInterface
{
    /**
     * @param MoneyInterface $money
     *
     * @throws MoneyException
     *
     * @return mixed
     */
    public function queryFrom(MoneyInterface $money);
}
