<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkProviderInterface;
use SonsOfPHP\Component\Link\EvolvableLinkProvider;
use SonsOfPHP\Component\Link\Link;
use SonsOfPHP\Component\Link\LinkProvider;

#[CoversClass(EvolvableLinkProvider::class)]
#[UsesClass(Link::class)]
#[UsesClass(LinkProvider::class)]
final class EvolvableLinkProviderTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new EvolvableLinkProvider();

        $this->assertInstanceOf(EvolvableLinkProviderInterface::class, $provider);
    }

    public function testWithLink(): void
    {
        $provider = new EvolvableLinkProvider();
        $this->assertCount(0, $provider->getLinks());

        $provider = $provider->withLink(new Link('https://docs.sonsofphp.com'));
        $this->assertCount(1, $provider->getLinks());
    }

    public function testWithoutLink(): void
    {
        $provider = new EvolvableLinkProvider([
            $link = new Link('https://docs.sonsofphp.com'),
        ]);
        $this->assertCount(1, $provider->getLinks());
        $this->assertCount(0, $provider->withoutLink($link)->getLinks());
    }
}
