<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ContextInterface extends \ArrayAccess, \IteratorAggregate
{
    /**
     * Returns all key/value pairs
     */
    public function all(): array;
}
