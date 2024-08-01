<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pager;

use Countable;
use InvalidArgumentException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AdapterInterface extends Countable
{
    /**
     * This will return part of the total results
     *
     * Offset is where the results to be returned will start, for example, if
     * offset is 0, it will start with the first record and return $length
     *
     * Offset should always be 0 or greater
     *
     * Length is how many results to return. For example, if length is 10, a
     * MAXIMUM of 10 results will be returned
     *
     * Length MUST always be a positive number that is 1 or greater OR be null
     *
     * If null is passed in as length, this should return ALL the results.
     *
     * If the total number of results is less than length, an exception must
     * not be thrown.
     *
     * @throws InvalidArgumentException
     *   If offset or length is invalid, this expection will be thrown
     */
    public function getSlice(int $offset, ?int $length): iterable;
}
