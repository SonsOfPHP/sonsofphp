<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\DBAL\Pager\Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\DBAL\Pager\QueryBuilderAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

/**
 * @uses \SonsOfPHP\Bridge\Doctrine\DBAL\Pager\QueryBuilderAdapter
 * @coversNothing
 */
#[CoversClass(QueryBuilderAdapter::class)]
final class QueryBuilderAdapterTest extends TestCase
{
    private MockObject $builder;

    private MockObject $result;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(QueryBuilder::class);
        $this->result = $this->createMock(Result::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $adapter = new QueryBuilderAdapter($this->builder, function (QueryBuilder $builder): void {});

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testCount(): void
    {
        $this->builder->method('executeQuery')->willReturn($this->result);
        $this->builder->expects($this->once())->method('select');

        $this->result->method('fetchOne')->willReturn(123);

        $adapter = new QueryBuilderAdapter($this->builder, function (QueryBuilder $builder): void {
            $builder->select('COUNT()');
        });

        $this->assertCount(123, $adapter);
    }

    public function testSlice(): void
    {
        $this->builder
            ->expects($this->once())
            ->method('setFirstResult')
            ->with($this->identicalTo(0))
            ->willReturn($this->builder)
        ;
        $this->builder
            ->expects($this->once())
            ->method('setMaxResults')
            ->with($this->identicalTo(100))
            ->willReturn($this->builder)
        ;

        $adapter = new QueryBuilderAdapter($this->builder, function (QueryBuilder $builder): void {});

        $adapter->getSlice(0, 100);
    }
}
