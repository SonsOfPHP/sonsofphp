<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\Collections\Pager;

use Doctrine\Common\Collections\ArrayCollection;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ArrayCollectionAdapter implements AdapterInterface
{
    public function __construct(
        private ArrayCollection $collection,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice(int $offset, ?int $length): iterable
    {
        return $this->collection->slice($offset, $length);
    }
}
