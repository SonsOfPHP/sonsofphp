<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money;

use IteratorAggregate;
use Generator;

/**
 * Currency Iterator
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CurrencyIterator implements Iterator
{
    private array $currencies = [];

    /**
     */
    public function __construct(array $currencies = [])
    {
        foreach ($currencies as $currency) {
            $this->add($currency);
        }
    }

    /**
     */
    public function add(CurrencyInterface $currency)
    {
        $this->currencies[] = $currency;
    }

    /**
     * @return Generator
     */
    public function getIterator(): Generator
    {
        foreach ($this->currencies as $currency) {
            yield $currency;
        }
    }
}
