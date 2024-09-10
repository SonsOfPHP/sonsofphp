<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;
use SonsOfPHP\Component\Link\Link;

/**
 * @uses \SonsOfPHP\Component\Link\Link
 * @coversNothing
 */
#[CoversClass(Link::class)]
final class LinkTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertInstanceOf(LinkInterface::class, $link);
    }

    public function testItWillThrowInvalidArgumentException(): void
    {
        $this->expectException('InvalidArgumentException');
        new Link();
    }

    public function testConstructWithRelsArray(): void
    {
        $link = new Link('https://docs.sonsofphp.com', rels: ['next', 'prev']);

        $this->assertCount(2, $link->getRels());
        $this->assertContains('next', $link->getRels());
        $this->assertContains('prev', $link->getRels());
    }

    public function testConstructWithRelsString(): void
    {
        $link = new Link('https://docs.sonsofphp.com', rels: 'next');

        $this->assertCount(1, $link->getRels());
        $this->assertContains('next', $link->getRels());
    }

    public function testGetHref(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertSame('https://docs.sonsofphp.com', $link->getHref());
    }

    public function testIsTemplated(): void
    {
        $link = new Link('https://docs.sonsofphp.com/components/{component}');

        $this->assertTrue($link->isTemplated());
    }

    public function testGetRels(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertCount(0, $link->getRels());
    }

    public function testGetAttributes(): void
    {
        $link = new Link('https://docs.sonsofphp.com');

        $this->assertCount(0, $link->getAttributes());
    }
}
