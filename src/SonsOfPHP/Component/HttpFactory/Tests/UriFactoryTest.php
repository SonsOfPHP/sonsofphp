<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpFactory\UriFactory;
use SonsOfPHP\Component\HttpMessage\Uri;

#[CoversClass(UriFactory::class)]
#[UsesClass(Uri::class)]
final class UriFactoryTest extends TestCase
{
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UriFactoryInterface::class, new UriFactory());
    }

    public function testCreateUriWorksAsExpected(): void
    {
        $factory = new UriFactory();
        $this->assertInstanceOf(UriInterface::class, $factory->createUri('https://docs.sonsofphp.com'));
    }
}
