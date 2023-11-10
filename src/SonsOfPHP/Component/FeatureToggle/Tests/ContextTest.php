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
     * @covers ::get
     * @covers ::has
     * @covers ::set
     */
    public function testItWorksCorrectly(): void
    {
        $context = new Context();

        $this->assertFalse($context->has('test'));
        $this->assertNull($context->get('test'), 'Assert NULL is returned if not set');

        $context->set('test', 'value');
        $this->assertSame('value', $context->get('test'));
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
}
