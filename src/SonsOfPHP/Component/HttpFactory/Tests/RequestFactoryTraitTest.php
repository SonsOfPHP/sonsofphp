<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SonsOfPHP\Component\HttpFactory\RequestFactoryTrait;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\Uri;

#[CoversClass(RequestFactoryTrait::class)]
#[UsesClass(Request::class)]
#[UsesClass(Uri::class)]
final class RequestFactoryTraitTest extends TestCase
{
    public function testCreateRequestWorksAsExpected(): void
    {
        $factory = $this->getMockForTrait(RequestFactoryTrait::class);

        $this->assertInstanceOf(RequestInterface::class, $factory->createRequest('GET', 'https://docs.sonsofphp.com'));
    }
}
