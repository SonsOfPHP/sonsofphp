<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Search;

/**
 * Backends should know the index to use and all the other information, for
 * example, if the backend used as a Database backend, it should know which
 * table to use to search
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface BackendInterface
{
    //public function indexDocument(mixed $document): void;
    //public function updateDocument(mixed $document): void;
    //public function deleteDocument(mixed $document): void;
    //public function getDocument(mixed $document): void;

    public function query(QueryInterface|string $query): iterable;
}
