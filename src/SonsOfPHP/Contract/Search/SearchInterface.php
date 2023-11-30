<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Search;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SearchInterface
{
    public function getBackend(): BackendInterface;

    public function query(QueryInterface $query): iterable;
}
