<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Search\Query;
use SonsOfPHP\Contract\Search\QueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Search\Query
 *
 * @uses \SonsOfPHP\Component\Search\Query
 */
final class QueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new Query();

        $this->assertInstanceOf(QueryInterface::class, $query);
    }

    /**
     * @covers ::getOffset
     */
    public function testGetOffset(): void
    {
        $query = new Query();

        $this->assertSame(0, $query->getOffset());
        $query['offset'] = 100;
        $this->assertSame(100, $query->getOffset());
    }

    /**
     * @covers ::getLength
     */
    public function testGetLength(): void
    {
        $query = new Query();

        $this->assertNull($query->getLength());
        $query['length'] = 100;
        $this->assertSame(100, $query->getLength());
    }

    /**
     * @covers ::has
     */
    public function testHas(): void
    {
        $query = new Query();

        // defaults
        $this->assertTrue($query->has('offset'));
        $this->assertTrue($query->has('length'));

        $this->assertFalse($query->has('testing'));
        $query->set('testing', 'test');
        $this->assertTrue($query->has('testing'));
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $query = new Query();

        // defaults
        $this->assertSame(0, $query->get('offset'));
        $this->assertNull($query->get('length'));

        $value = 'test value';
        $this->assertSame($query, $query->set('value', $value));
        $this->assertSame($value, $query->get('value'));
    }

    /**
     * @covers ::get
     */
    public function testGetWhenFieldDoesNotExist(): void
    {
        $query = new Query();

        $this->assertNull($query->get('not a real field'));
    }

    /**
     * @covers ::remove
     */
    public function testRemove(): void
    {
        $query = new Query();
        $this->assertFalse($query->has('unit.test'));
        $query->set('unit.test', 'value');
        $this->assertTrue($query->has('unit.test'));

        $this->assertSame($query, $query->remove('unit.test'));
        $this->assertFalse($query->has('unit.test'));
    }

    /**
     * @covers ::remove
     */
    public function testRemoveWithFieldThatDoesNotExist(): void
    {
        $query = new Query();
        $this->assertFalse($query->has('unit.test'));
        $this->assertSame($query, $query->remove('unit.test'));
        $this->assertFalse($query->has('unit.test'));
    }

    /**
     * @covers ::remove
     */
    public function testRemoveCannotRemoveField(): void
    {
        $query = new Query();

        $this->assertTrue($query->has('offset'));
        $this->assertTrue($query->has('length'));

        $this->assertSame($query, $query->remove('offset'));
        $this->assertSame($query, $query->remove('length'));

        $this->assertTrue($query->has('offset'));
        $this->assertTrue($query->has('length'));
    }

    /**
     * @covers ::set
     */
    public function testItCanSetOffset(): void
    {
        $query = new Query();

        $this->assertSame($query, $query->set('offset', 100));
        $this->assertSame(100, $query->get('offset'));
    }

    /**
     * @covers ::set
     */
    public function testItCanSetLength(): void
    {
        $query = new Query();

        $this->assertSame($query, $query->set('length', 100));
        $this->assertSame(100, $query->get('length'));
    }

    /**
     * @covers ::set
     */
    public function testSet(): void
    {
        $query = new Query();

        $this->assertFalse($query->has('field'));
        $this->assertSame($query, $query->set('field', 'value'));
        $this->assertTrue($query->has('field'));
        $this->assertSame('value', $query->get('field'));
    }

    /**
     * @covers ::offsetExists
     * @covers ::offsetGet
     * @covers ::offsetSet
     * @covers ::offsetUnset
     */
    public function testItWorksLikeAnArray(): void
    {
        $query = new Query();

        $query['field'] = 'value';
        $this->assertSame('value', $query['field']);

        $this->assertArrayHasKey('field', $query);
        unset($query['field']);
        $this->assertArrayNotHasKey('field', $query);
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $query = new Query();

        // default
        $this->assertSame('{"offset":0,"length":null}', json_encode($query));
    }
}
