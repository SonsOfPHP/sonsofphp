<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\ContextInterface;
use PHPUnit\Framework\TestCase;

final class ContextTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $context = new Context();

        $this->assertInstanceOf(ContextInterface::class, $context);
    }

    public function testItWorksCorrectly(): void
    {
        $context = new Context();

        $this->assertFalse($context->has('test'));
        $this->assertNull($context->get('test'), 'Assert NULL is returned if not set');

        $context->set('test', 'value');
        $this->assertSame('value', $context->get('test'));
    }
}
