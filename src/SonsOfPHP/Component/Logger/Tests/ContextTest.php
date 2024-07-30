<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Contract\Logger\ContextInterface;

/**
 * @uses \SonsOfPHP\Component\Logger\Context
 * @coversNothing
 */
#[CoversClass(Context::class)]
final class ContextTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $context = new Context();

        $this->assertInstanceOf(ContextInterface::class, $context);
    }

    public function testOffsetSetWithComplex(): void
    {
        $context = new Context();
        $context['test'] = [
            'key' => 'value',
        ];

        $this->assertSame('value', $context['test']['key']);
    }

    public function testAll(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $context['test'] = 'testing';

        $this->assertCount(2, $context->all());
    }

    public function testOffsetGet(): void
    {
        $context = new Context();

        $context['test'] = 'testing';
        $this->assertSame('testing', $context['test']);
    }

    public function testOffsetExists(): void
    {
        $context = new Context();

        $this->assertFalse(isset($context['test']));
        $this->assertEmpty($context['test']);

        $context['test'] = 'testing';
        $this->assertTrue(isset($context['test']));
        $this->assertNotEmpty($context['test']);
    }

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
