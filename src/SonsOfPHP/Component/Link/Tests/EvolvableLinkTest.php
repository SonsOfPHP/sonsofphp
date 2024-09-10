<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkInterface;
use SonsOfPHP\Component\Link\EvolvableLink;
use SonsOfPHP\Component\Link\Link;
use Stringable;

#[CoversClass(EvolvableLink::class)]
#[UsesClass(Link::class)]
final class EvolvableLinkTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $link = new EvolvableLink();

        $this->assertInstanceOf(EvolvableLinkInterface::class, $link);
    }

    public function testWithHrefWhenStringable(): void
    {
        $href = new class implements Stringable {
            public function __toString(): string
            {
                return 'https://docs.sonsofphp.com';
            }
        };
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withHref($href));
        $this->assertSame((string) $href, $link->withHref($href)->getHref());
    }

    public function testWithHref(): void
    {
        $href = 'https://docs.sonsofphp.com';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withHref($href));
        $this->assertSame($href, $link->withHref($href)->getHref());
    }

    public function testWithRelWhenAlreadyExists(): void
    {
        $rel = 'next';
        $link = new EvolvableLink(rels: $rel);

        $this->assertSame($link, $link->withRel($rel));
    }

    public function testWithRel(): void
    {
        $rel = 'next';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withRel($rel));
        $this->assertContains($rel, $link->withRel($rel)->getRels());
    }

    public function testWithoutRelWhenRelNotPresent(): void
    {
        $rel = 'next';
        $link = new EvolvableLink();

        $this->assertSame($link, $link->withoutRel($rel));
        $this->assertNotContains($rel, $link->withoutRel($rel)->getRels());
    }

    public function testWithoutRel(): void
    {
        $rel = 'next';
        $link = new EvolvableLink(rels: $rel);

        $this->assertNotSame($link, $link->withoutRel($rel));
        $this->assertNotContains($rel, $link->withoutRel($rel)->getRels());
    }

    public function testWithAttribute(): void
    {
        $key = 'key';
        $value = 'value';
        $link = new EvolvableLink();

        $this->assertNotSame($link, $link->withAttribute($key, $value));
        $this->assertArrayHasKey($key, $link->withAttribute($key, $value)->getAttributes());
    }

    public function testWithoutAttributeWhenKeyDoesNotExist(): void
    {
        $key = 'key';
        $link = new EvolvableLink();

        $this->assertSame($link, $link->withoutAttribute($key));
        $this->assertArrayNotHasKey($key, $link->withoutAttribute($key)->getAttributes());
    }

    public function testWithoutAttribute(): void
    {
        $key = 'key';
        $value = 'value';
        $link = new EvolvableLink(attributes: [$key => $value]);

        $this->assertNotSame($link, $link->withoutAttribute($key));
        $this->assertArrayNotHasKey($key, $link->withoutAttribute($key)->getAttributes());
    }
}
