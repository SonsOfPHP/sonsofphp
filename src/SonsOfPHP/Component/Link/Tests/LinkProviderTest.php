<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Link\LinkProviderInterface;
use SonsOfPHP\Component\Link\Link;
use SonsOfPHP\Component\Link\LinkProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Link\LinkProvider
 *
 * @uses \SonsOfPHP\Component\Link\Link
 * @uses \SonsOfPHP\Component\Link\LinkProvider
 */
final class LinkProviderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new LinkProvider();

        $this->assertInstanceOf(LinkProviderInterface::class, $provider);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWilThrowException(): void
    {
        $this->expectException('InvalidArgumentException');
        $provider = new LinkProvider([
            'nope',
        ]);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructCanAddLinks(): void
    {
        $provider = new LinkProvider([
            new Link('https://docs.sonsofphp.com', rels: ['next']),
        ]);

        $this->assertCount(1, $provider->getLinks());
    }

    /**
     * @covers ::getLinks
     */
    public function testGetLinks(): void
    {
        $provider = new LinkProvider();

        $this->assertCount(0, $provider->getLinks());
    }

    /**
     * @covers ::getLinksByRel
     */
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
