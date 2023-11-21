<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\ORM\Pager\Tests;

use Doctrine\ORM\QueryBuilder;
use SonsOfPHP\Contract\Pager\AdapterInterface;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\ORM\Pager\QueryBuilderAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Doctrine\ORM\Pager\QueryBuilderAdapter
 *
 * @uses \SonsOfPHP\Bridge\Doctrine\ORM\Pager\QueryBuilderAdapter
 */
final class QueryBuilderAdapterTest extends TestCase
{
    private $builder;

    public function setUp(): void
    {
        $this->builder = $this->createMock(QueryBuilder::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $adapter = new QueryBuilderAdapter($this->builder);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
