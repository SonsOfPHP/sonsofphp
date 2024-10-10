<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpFactory\UriFactory;
use SonsOfPHP\Component\HttpMessage\Uri;

/**
 * @internal
 */
#[CoversClass(UriFactory::class)]
#[CoversNothing]
final class UriFactoryTest extends TestCase
{
    #[CoversNothing]
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UriFactoryInterface::class, new UriFactory());
    }

    #[UsesClass(Uri::class)]
    public function testCreateUriWorksAsExpected(): void
    {
        $factory = new UriFactory();
        $this->assertInstanceOf(UriInterface::class, $factory->createUri('https://docs.sonsofphp.com'));
    }
}
