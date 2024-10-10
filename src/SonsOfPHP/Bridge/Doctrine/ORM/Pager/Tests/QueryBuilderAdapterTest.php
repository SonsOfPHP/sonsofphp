<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\ORM\Pager\Tests;

use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\ORM\Pager\QueryBuilderAdapter;
use SonsOfPHP\Contract\Pager\AdapterInterface;

#[CoversClass(QueryBuilderAdapter::class)]
#[UsesClass(QueryBuilderAdapter::class)]
final class QueryBuilderAdapterTest extends TestCase
{
    private MockObject $builder;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(QueryBuilder::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $adapter = new QueryBuilderAdapter($this->builder);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
