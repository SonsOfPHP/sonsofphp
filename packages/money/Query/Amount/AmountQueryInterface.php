<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Query\Amount;

use SonsOfPHP\Component\Money\AmountInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Query\QueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AmountQueryInterface extends QueryInterface
{
    /**
     * @throws MoneyException
     */
    public function queryFrom(AmountInterface $amount);
}
