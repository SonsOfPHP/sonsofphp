<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\Collections\Pager;

use Doctrine\DBAL\Query\QueryBuilder;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class QueryBuilderAdapter implements AdapterInterface
{
    public function __construct(
        private QueryBuilder $builder,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        throw new \Exception('@todo');
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice(int $offset, ?int $length): iterable
    {
        throw new \Exception('@todo');
    }
}
