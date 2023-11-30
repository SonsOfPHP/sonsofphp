<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search\Null;

use SonsOfPHP\Contract\Search\BackendInterface;
use SonsOfPHP\Contract\Search\QueryInterface;

/**
 * Mock Backend is mainly used for testing. It allows you to set the results
 * that will be returned
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MockBackend implements BackendInterface
{
    public function __construct(private array $results = []) {}

    public function pushResult(mixed $result): self
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function query(QueryInterface|string $query): iterable
    {
        return $this->results;
    }
}
