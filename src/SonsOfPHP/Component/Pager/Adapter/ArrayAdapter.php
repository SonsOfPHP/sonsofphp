<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Adapter;

use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ArrayAdapter implements AdapterInterface
{
    public function __construct(
        private array $array,
    ) {}

    public function count(): int
    {
        return count($this->array);
    }

    public function getSlice(int $offset, ?int $length): iterable
    {
        return array_slice($this->array, $offset, $length);
    }
}
