<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search;

use SonsOfPHP\Contract\Search\QueryInterface;
use SonsOfPHP\Contract\Search\BackendInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Search implements SearchInterface
{
    public function __construct(private BackendInterface $backend) {}

    public function getBackend(): BackendInterface
    {
        return $this->backend;
    }

    public function query(QueryInterface $query): iterable
    {
        return $this->backend->query($query);
    }
}
