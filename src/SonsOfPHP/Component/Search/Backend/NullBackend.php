<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search\Null;

use SonsOfPHP\Contract\Search\BackendInterface;
use SonsOfPHP\Contract\Search\QueryInterface;

/**
 * Null Backend is used for testing. It does not return any data and all
 * methods will be successful
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NullBackend implements BackendInterface
{
    /**
     * {@inheritdoc}
     */
    public function query(QueryInterface|string $query): iterable
    {
        return [];
    }
}
