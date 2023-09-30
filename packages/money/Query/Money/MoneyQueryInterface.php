<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Money;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\MoneyInterface;
use SonsOfPHP\Component\Money\Query\QueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoneyQueryInterface extends QueryInterface
{
    /**
     * @throws MoneyException
     */
    public function queryFrom(MoneyInterface $money);
}
