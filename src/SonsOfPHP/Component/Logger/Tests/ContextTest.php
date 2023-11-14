<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Contract\Logger\ContextInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Context
 *
 * @uses \SonsOfPHP\Component\Logger\Context
 */
final class ContextTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $context = new Context();

        $this->assertInstanceOf(ContextInterface::class, $context);
    }

    /**
     * @covers ::offsetSet
     */
    public function testOffsetSetWithComplex(): void
    {
        $context = new Context();
        $context['test'] = [
            'key' => 'value',
        ];

        $this->assertSame('value', $context['test']['key']);
    }

    /**
     * @covers ::all
     */
    public function testAll(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $context['test'] = 'testing';

        $this->assertCount(2, $context->all());
    }

    /**
     * @covers ::offsetGet
     */
    public function testOffsetGet(): void
    {
        $context = new Context();

        $context['test'] = 'testing';
        $this->assertSame('testing', $context['test']);
    }

    /**
     * @covers ::offsetExists
     */
    public function testOffsetExists(): void
    {
        $context = new Context();

        $this->assertFalse(isset($context['test']));
        $this->assertEmpty($context['test']);

        $context['test'] = 'testing';
        $this->assertTrue(isset($context['test']));
        $this->assertNotEmpty($context['test']);
    }

    /**
     * @covers ::offsetUnset
     */
    public function testOffsetUnset(): void
    {
        $context = new Context();

        $context['key'] = 'value';
        $this->assertTrue(isset($context['key']));
        $this->assertNotEmpty($context['key']);

        unset($context['key']);
        $this->assertFalse(isset($context['key']));
        $this->assertEmpty($context['key']);
    }

    /**
     * @covers ::offsetSet
     */
    public function testOffsetSet(): void
    {
        $context = new Context();

        $context['test'] = 'unit test';
        $this->assertTrue(isset($context['test']));
        $this->assertNotEmpty($context['test']);

        $context['key'] = 'value';
        $this->assertTrue(isset($context['key']));
        $this->assertNotEmpty($context['key']);
    }
}
