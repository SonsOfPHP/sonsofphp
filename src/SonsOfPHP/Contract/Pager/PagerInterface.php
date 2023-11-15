<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pager;

/**
 * The pager should take an adapter and optional arguments when being created.
 *
 * Example:
 *   new Pager($adapter, ['current_page' => 2, 'max_per_page' => 10]);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PagerInterface extends \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * This will return the current results based on the current page and max
     * results per page
     */
    public function getCurrentPageResults(): iterable;

    public function getTotalResults(): int;

    public function getTotalPages(): int;

    public function haveToPaginate(): bool;

    public function hasPreviousPage(): bool;

    /**
     * If there is no previous page, this will return null
     */
    public function getPreviousPage(): ?int;

    public function hasNextPage(): bool;

    /**
     * If there is no next page, this will return null
     */
    public function getNextPage(): ?int;

    /**
     * Returns the current page
     *
     * This should default to 1 if no current page is set.
     */
    public function getCurrentPage(): int;

    /**
     * The page must be 1 or greater
     *
     * If the page is out of bounds, this may throw an exception
     *
     * @throws \InvalidArgumentException
     *   If $page is invalid
     */
    public function setCurrentPage(int $page): void;

    /**
     * This should return a default value if not set
     *
     * If max per page is set to null, this will return null
     */
    public function getMaxPerPage(): ?int;

    /**
     * The max per page should be a value of 1 or greater.
     *
     * If the value is set to null, and results are grabbed, all the results
     * will be returned.
     *
     * @throws \InvalidArgumentException
     *   If $maxPerPage is invalid
     */
    public function setMaxPerPage(?int $maxPerPage): void;
}
