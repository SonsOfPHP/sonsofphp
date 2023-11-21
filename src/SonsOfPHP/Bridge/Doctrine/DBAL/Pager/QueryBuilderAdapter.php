<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\DBAL\Pager;

use Doctrine\DBAL\Query\QueryBuilder;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * Usage:
 *    $adapter = new QueryBuilderAdapter($queryBuilder, function (QueryBuilder $builder): void {
 *        $builder->select('COUNT(DISTINCT e.id) AS cnt');
 *    });
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class QueryBuilderAdapter implements AdapterInterface
{
    private $countQuery;

    public function __construct(
        private readonly QueryBuilder $builder,
        callable $countQuery,
    ) {
        $this->countQuery = $countQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        $builder  = clone $this->builder;
        $callable = $this->countQuery;

        $callable($builder);
        $builder->setMaxResults(1);

        return (int) $builder->executeQuery()->fetchOne();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice(int $offset, ?int $length): iterable
    {
        $builder = clone $this->builder;

        return $builder
            ->setFirstResult($offset)
            ->setMaxResults($length)
            ->executeQuery()
            ->fetchAllAssociative()
        ;
    }
}
