<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\ORM\Pager;

use Doctrine\ORM\QueryBuilder;
use SonsOfPHP\Contract\Pager\AdapterInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class QueryBuilderAdapter implements AdapterInterface
{
    private readonly Paginator $paginator;

    public function __construct(
        QueryBuilder $builder,
    ) {
        $this->paginator = new Paginator($builder, fetchJoinCollection: true);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice(int $offset, ?int $length): iterable
    {
        $query = $this->paginator->getQuery();
        $query
            ->setFirstResult($offset)
            ->setMaxResults($length)
        ;

        return $this->paginator->getIterator();
    }
}
