<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\UriFactory;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\UriFactory
 *
 * @internal
 */
final class UriFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UriFactoryInterface::class, new UriFactory());
    }

    /**
     * @covers ::createUri
     * @uses \SonsOfPHP\Component\HttpMessage\Uri
     */
    public function testCreateUriWorksAsExpected(): void
    {
        $factory = new UriFactory();
        $this->assertInstanceOf(UriInterface::class, $factory->createUri('https://docs.sonsofphp.com'));
    }
}
