<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkProviderInterface;
use SonsOfPHP\Component\Link\EvolvableLinkProvider;
use SonsOfPHP\Component\Link\Link;

/**
 *
 * @uses \SonsOfPHP\Component\Link\Link
 * @uses \SonsOfPHP\Component\Link\EvolvableLinkProvider
 * @uses \SonsOfPHP\Component\Link\LinkProvider
 * @coversNothing
 */
#[CoversClass(EvolvableLinkProvider::class)]
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
