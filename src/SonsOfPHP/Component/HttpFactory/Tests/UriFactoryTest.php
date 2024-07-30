<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpFactory\UriFactory;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(UriFactory::class)]
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
     * @uses \SonsOfPHP\Component\HttpMessage\Uri
     */
    public function testCreateUriWorksAsExpected(): void
    {
        $factory = new UriFactory();
        $this->assertInstanceOf(UriInterface::class, $factory->createUri('https://docs.sonsofphp.com'));
    }
}
