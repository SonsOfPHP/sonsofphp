<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Contract\FeatureToggle\ContextInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Context
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 */
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

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertSame('value', $context->get('key'));
    }

    /**
     * @covers ::get
     */
    public function testGetWhenThereIsValueForKey(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertSame('value', $context->get('key'));
    }

    /**
     * @covers ::get
     */
    public function testGetWhenThereIsNoValueForKey(): void
    {
        $context = new Context();
        $this->assertNull($context->get('test'));
    }

    /**
     * @covers ::get
     */
    public function testGetWhenThereIsNoValueForKeyAndDefaultValueIsProvided(): void
    {
        $context = new Context();
        $this->assertTrue($context->get('test', true));
    }

    /**
     * @covers ::get
     */
    public function testGetWithNoArgumentsWillReturnAll(): void
    {
        $context = new Context();
        $context->set('key', 'value');
        $parameters = $context->get();
        $this->assertIsArray($parameters);
        $this->assertArrayHasKey('key', $parameters);
    }

    /**
     * @covers ::set
     */
    public function testWorksAsExpected(): void
    {
        $context = new Context();
        $context->set('key', 'value');
        $this->assertSame('value', $context->get('key'));
    }

    /**
     * @covers ::has
     */
    public function testHasWillReturnTrueWhenKeyExists(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertTrue($context->has('key'));
    }

    /**
     * @covers ::has
     */
    public function testHasWillReturnFalseWhenKeyExists(): void
    {
        $context = new Context([
            'key' => 'value',
        ]);
        $this->assertFalse($context->has('value'));
    }
}
