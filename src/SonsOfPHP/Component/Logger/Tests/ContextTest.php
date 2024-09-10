<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Contract\Logger\ContextInterface;
use stdClass;

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

        $this->assertArrayNotHasKey('test', $context);
        $this->assertEmpty($context['test']);

        $context['test'] = 'testing';
        $this->assertArrayHasKey('test', $context);
        $this->assertNotEmpty($context['test']);
    }

    public function testOffsetUnset(): void
    {
        $context = new Context();

        $context['key'] = 'value';
        $this->assertArrayHasKey('key', $context);
        $this->assertNotEmpty($context['key']);

        unset($context['key']);
        $this->assertArrayNotHasKey('key', $context);
        $this->assertEmpty($context['key']);
    }

    public function testOffsetSet(): void
    {
        $context = new Context();

        $context['test'] = 'unit test';
        $this->assertArrayHasKey('test', $context);
        $this->assertNotEmpty($context['test']);

        $context['key'] = 'value';
        $this->assertArrayHasKey('key', $context);
        $this->assertNotEmpty($context['key']);
    }

    public function testItWillThrowExceptionDuringOffsetExistsWithInvalidOffset(): void
    {
        $context = new Context();

        $this->expectException('InvalidArgumentException');
        $context[new stdClass()];
    }

    public function testItWillThrowExceptionDuringOffsetGetWithInvalidOffset(): void
    {
        $context = new Context();

        $this->expectException('InvalidArgumentException');
        $context[new stdClass()];
    }

    public function testItWillThrowExceptionDuringOffsetSetWithInvalidOffset(): void
    {
        $context = new Context();

        $this->expectException('InvalidArgumentException');
        $context[new stdClass()] = 'test';
    }

    public function testItWillThrowExceptionDuringOffsetSetWithInvalidOffsetValue(): void
    {
        $context = new Context();

        $this->expectException('InvalidArgumentException');
        $context['test'] = new stdClass();
    }

    public function testItWillThrowExceptionDuringOffsetUnsetWithInvalidOffset(): void
    {
        $context = new Context();

        $this->expectException('InvalidArgumentException');
        unset($context[new stdClass()]);
    }
}
