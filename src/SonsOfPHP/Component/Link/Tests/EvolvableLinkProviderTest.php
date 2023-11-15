<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Link\Link;
use SonsOfPHP\Component\Link\EvolvableLinkProvider;
use Psr\Link\EvolvableLinkProviderInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Link\EvolvableLinkProvider
 *
 * @uses \SonsOfPHP\Component\Link\Link
 * @uses \SonsOfPHP\Component\Link\EvolvableLinkProvider
 * @uses \SonsOfPHP\Component\Link\LinkProvider
 */
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

    /**
     * @covers ::withLink
     */
    public function testWithLink(): void
    {
        $provider = new EvolvableLinkProvider();
        $this->assertCount(0, $provider->getLinks());

        $provider = $provider->withLink(new Link('https://docs.sonsofphp.com'));
        $this->assertCount(1, $provider->getLinks());
    }

    /**
     * @covers ::withoutLink
     */
    public function testWithoutLink(): void
    {
        $provider = new EvolvableLinkProvider([
            $link = new Link('https://docs.sonsofphp.com'),
        ]);
        $this->assertCount(1, $provider->getLinks());
        $this->assertCount(0, $provider->withoutLink($link)->getLinks());
    }
}
