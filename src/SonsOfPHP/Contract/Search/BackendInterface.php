<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Search;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface BackendInterface
{
    public function indexDocument(mixed $document): void;
    public function updateDocument(mixed $document): void;
    public function deleteDocument(mixed $document): void;
    public function getDocument(mixed $document): void;

    public function query(QueryInterface $query): iterable;
}
