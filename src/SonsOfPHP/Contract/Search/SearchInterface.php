<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Search;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SearchInterface
{
    /**
     */
    public function getBackend(): BackendInterface;

    /**
     * Takes a QueryInterface or a String
     *
     * If a string is passed in, it MUST be able to be converted to a QueryInterface
     *
     * @throws \SonsOfPHP\Contract\Search\SearchExceptionInterface
     *   If the $query is invalid for any reason
     */
    public function query(QueryInterface|string $query): iterable;
}
