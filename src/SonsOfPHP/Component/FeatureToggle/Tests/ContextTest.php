<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Contract\FeatureToggle\ContextInterface;

/**
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 * @coversNothing
 */
#[CoversClass(Context::class)]
final class ContextTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $context = new Context();

        $this->assertInstanceOf(ContextInterface::class, $context);
    }

    public function testConstruct(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertSame('value', $context->get('key'));
    }

    public function testGetWhenThereIsValueForKey(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertSame('value', $context->get('key'));
    }

    public function testGetWhenThereIsNoValueForKey(): void
    {
        $context = new Context();
        $this->assertNull($context->get('test'));
    }

    public function testGetWhenThereIsNoValueForKeyAndDefaultValueIsProvided(): void
    {
        $context = new Context();
        $this->assertTrue($context->get('test', true));
    }

    public function testGetWithNoArgumentsWillReturnAll(): void
    {
        $context = new Context();
        $context->set('key', 'value');

        $parameters = $context->get();
        $this->assertIsArray($parameters);
        $this->assertArrayHasKey('key', $parameters);
    }

    public function testWorksAsExpected(): void
    {
        $context = new Context();
        $context->set('key', 'value');
        $this->assertSame('value', $context->get('key'));
    }

    public function testHasWillReturnTrueWhenKeyExists(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertTrue($context->has('key'));
    }

    public function testHasWillReturnFalseWhenKeyExists(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertFalse($context->has('value'));
    }
}
