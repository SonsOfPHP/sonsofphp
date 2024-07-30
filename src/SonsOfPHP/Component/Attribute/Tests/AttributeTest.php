<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Attribute\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Attribute\Attribute;
use SonsOfPHP\Contract\Attribute\AttributeInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Attribute\Attribute
 */
final class AttributeTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        $this->model = new Attribute();
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterfaces(): void
    {
        $this->assertInstanceOf(AttributeInterface::class, $this->model);
    }

    /**
     * @covers ::getCode
     * @covers ::setCode
     */
    public function testCode(): void
    {
        $this->assertNull($this->model->getCode());
        $this->model->setCode('sku');
        $this->assertSame('sku', $this->model->getCode());
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName(): void
    {
        $this->assertNull($this->model->getName());
        $this->model->setName('Test Attribute');
        $this->assertSame('Test Attribute', $this->model->getName());
    }

    /**
     * @covers ::getPosition
     * @covers ::setPosition
     */
    public function testPosition(): void
    {
        $this->assertSame(0, $this->model->getPosition());
        $this->model->setPosition(100);
        $this->assertSame(100, $this->model->getPosition());
    }
}
