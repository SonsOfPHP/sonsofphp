<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkProviderInterface;
use SonsOfPHP\Component\Link\Link;
use SonsOfPHP\Component\Link\LinkProvider;

#[CoversClass(LinkProvider::class)]
#[UsesClass(Link::class)]
final class LinkProviderTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new LinkProvider();

        $this->assertInstanceOf(LinkProviderInterface::class, $provider);
    }

    public function testConstructWilThrowException(): void
    {
        $this->expectException('InvalidArgumentException');
        new LinkProvider([
            'nope',
        ]);
    }

    public function testConstructCanAddLinks(): void
    {
        $provider = new LinkProvider([
            new Link('https://docs.sonsofphp.com', rels: ['next']),
        ]);

        $this->assertCount(1, $provider->getLinks());
    }

    public function testGetLinks(): void
    {
        $provider = new LinkProvider();

        $this->assertEmpty($provider->getLinks());
    }

    public function testGetLinksByRel(): void
    {
        $provider = new LinkProvider([
            new Link('https://docs.sonsofphp.com', rels: ['next']),
        ]);

        $links = $provider->getLinksByRel('next');
        $links = iterator_to_array($links);

        $this->assertCount(1, $links);
    }
}
