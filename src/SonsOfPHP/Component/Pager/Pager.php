<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Pager;

use SonsOfPHP\Contract\Pager\AdapterInterface;
use SonsOfPHP\Contract\Pager\PagerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Pager implements PagerInterface
{
    private ?int $count = null;
    private int $currentPage = 1;
    private ?int $maxPerPage = 10;
    private ?iterable $results = null;

    /**
     * Available Options
     *   - current_page (default: 1)
     *   - max_per_page (default: 10)
     */
    public function __construct(
        private AdapterInterface $adapter,
        array $options = [],
    ) {
        if (array_key_exists('current_page', $options)) {
            $this->setCurrentPage($options['current_page']);
        }

        if (array_key_exists('max_per_page', $options)) {
            $this->setMaxPerPage($options['max_per_page']);
        }
    }

    public function getCurrentPageResults(): iterable
    {
        if (null === $this->results) {
            $offset = 0;
            if (null !== $this->getMaxPerPage()) {
                $offset = ($this->currentPage - 1) * $this->getMaxPerPage();
            }

            $this->results = $this->adapter->getSlice($offset, $this->getMaxPerPage());
        }

        return $this->results;
    }

    public function getTotalResults(): int
    {
        if (null === $this->count) {
            $this->count = $this->adapter->count();
        }

        return $this->count;
    }

    public function getTotalPages(): int
    {
        if (null === $this->getMaxPerPage() || 0 === $this->getTotalResults()) {
            return 1;
        }

        return (int) ceil($this->getTotalResults() / $this->getMaxPerPage());
    }

    public function haveToPaginate(): bool
    {
        if (null === $this->getMaxPerPage()) {
            return false;
        }

        return $this->getTotalResults() > $this->getMaxPerPage();
    }

    public function hasPreviousPage(): bool
    {
        return $this->getCurrentPage() > 1;
    }

    public function getPreviousPage(): ?int
    {
        if ($this->hasPreviousPage()) {
            return $this->getCurrentPage() - 1;
        }

        return null;
    }

    public function hasNextPage(): bool
    {
        return $this->getCurrentPage() < $this->getTotalPages();
    }

    public function getNextPage(): ?int
    {
        if ($this->hasNextPage()) {
            return $this->getCurrentPage() + 1;
        }

        return null;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $page): void
    {
        if (1 > $page) {
            throw new \InvalidArgumentException('$page is invalid');
        }

        $this->currentPage = $page;
        $this->results     = null;
    }

    public function getMaxPerPage(): ?int
    {
        return $this->maxPerPage;
    }

    public function setMaxPerPage(?int $maxPerPage): void
    {
        if (is_int($maxPerPage) && 1 > $maxPerPage) {
            throw new \InvalidArgumentException('maxPerPage is invalid');
        }

        $this->maxPerPage = $maxPerPage;
        $this->results    = null;
    }

    public function count(): int
    {
        return $this->getTotalResults();
    }

    public function getIterator(): \Traversable
    {
        $results = $this->getCurrentPageResults();

        if ($results instanceof \Iterator) {
            return $results;
        }

        if ($results instanceof \IteratorAggregate) {
            return $results->getIterator();
        }

        return new \ArrayIterator($results);
    }

    public function jsonSerialize(): array
    {
        $results = $this->getCurrentPageResults();

        if ($results instanceof \Traversable) {
            return iterator_to_array($results);
        }

        return $results;
    }
}
