<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;
use SonsOfPHP\Component\Link\Link;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Link\Link
 *
 * @uses \SonsOfPHP\Component\Link\Link
 */
final class LinkTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertInstanceOf(LinkInterface::class, $link);
    }

    /**
     * @covers ::__construct
     */
    public function testItWillThrowInvalidArgumentException(): void
    {
        $this->expectException('InvalidArgumentException');
        $link = new Link();
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithRelsArray(): void
    {
        $link = new Link('https://docs.sonsofphp.com', rels: ['next', 'prev']);

        $this->assertCount(2, $link->getRels());
        $this->assertContains('next', $link->getRels());
        $this->assertContains('prev', $link->getRels());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithRelsString(): void
    {
        $link = new Link('https://docs.sonsofphp.com', rels: 'next');

        $this->assertCount(1, $link->getRels());
        $this->assertContains('next', $link->getRels());
    }

    /**
     * @covers ::getHref
     */
    public function testGetHref(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertSame('https://docs.sonsofphp.com', $link->getHref());
    }

    /**
     * @covers ::isTemplated
     */
    public function testIsTemplated(): void
    {
        $link = new Link('https://docs.sonsofphp.com/components/{component}');

        $this->assertTrue($link->isTemplated());
    }

    /**
     * @covers ::getRels
     */
    public function testGetRels(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertCount(0, $link->getRels());
    }

    /**
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertCount(0, $link->getAttributes());
    }
}
