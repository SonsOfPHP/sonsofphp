<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkInterface;
use SonsOfPHP\Component\Link\EvolvableLink;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Link\EvolvableLink
 *
 * @uses \SonsOfPHP\Component\Link\EvolvableLink
 * @uses \SonsOfPHP\Component\Link\Link
 */
final class EvolvableLinkTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $link = new EvolvableLink();

        $this->assertInstanceOf(EvolvableLinkInterface::class, $link);
    }

    /**
     * @covers ::withHref
     */
    public function testWithHrefWhenStringable(): void
    {
        $href = new class () implements \Stringable {
            public function __toString(): string
            {
                return 'https://docs.sonsofphp.com';
            }
        };
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withHref($href));
        $this->assertSame((string) $href, $link->withHref($href)->getHref());
    }

    /**
     * @covers ::withHref
     */
    public function testWithHref(): void
    {
        $href = 'https://docs.sonsofphp.com';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withHref($href));
        $this->assertSame($href, $link->withHref($href)->getHref());
    }

    /**
     * @covers ::withRel
     */
    public function testWithRelWhenAlreadyExists(): void
    {
        $rel = 'next';
        $link = new EvolvableLink(rels: $rel);

        $this->assertSame($link, $link->withRel($rel));
    }

    /**
     * @covers ::withRel
     */
    public function testWithRel(): void
    {
        $rel = 'next';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withRel($rel));
        $this->assertContains($rel, $link->withRel($rel)->getRels());
    }

    /**
     * @covers ::withoutRel
     */
    public function testWithoutRelWhenRelNotPresent(): void
    {
        $rel = 'next';
        $link = new EvolvableLink();

        $this->assertSame($link, $link->withoutRel($rel));
        $this->assertNotContains($rel, $link->withoutRel($rel)->getRels());
    }

    /**
     * @covers ::withoutRel
     */
    public function testWithoutRel(): void
    {
        $rel = 'next';
        $link = new EvolvableLink(rels: $rel);

        $this->assertNotSame($link, $link->withoutRel($rel));
        $this->assertNotContains($rel, $link->withoutRel($rel)->getRels());
    }

    /**
     * @covers ::withAttribute
     */
    public function testWithAttribute(): void
    {
        $key = 'key';
        $value = 'value';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withAttribute($key, $value));
        $this->assertArrayHasKey($key, $link->withAttribute($key, $value)->getAttributes());
    }

    /**
     * @covers ::withoutAttribute
     */
    public function testWithoutAttributeWhenKeyDoesNotExist(): void
    {
        $key = 'key';
        $link = new EvolvableLink();

        $this->assertSame($link, $link->withoutAttribute($key));
        $this->assertArrayNotHasKey($key, $link->withoutAttribute($key)->getAttributes());
    }

    /**
     * @covers ::withoutAttribute
     */
    public function testWithoutAttribute(): void
    {
        $key = 'key';
        $value = 'value';
        $link = new EvolvableLink(attributes: [$key => $value]);

        $this->assertNotSame($link, $link->withoutAttribute($key));
        $this->assertArrayNotHasKey($key, $link->withoutAttribute($key)->getAttributes());
    }
}
