<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager\Adapter;

use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CallableAdapter implements AdapterInterface
{
    private $count;
    private $slice;

    public function __construct(callable $count, callable $slice)
    {
        $this->count = $count;
        $this->slice = $slice;
    }

    public function count(): int
    {
        return call_user_func($this->count);
    }

    public function getSlice(int $offset, ?int $length): iterable
    {
        return call_user_func($this->slice, $offset, $length);
    }
}
