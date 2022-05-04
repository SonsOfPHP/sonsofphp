<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query;

use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyQueryInterface extends QueryInterface
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
