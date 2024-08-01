<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SonsOfPHP\Component\HttpFactory\RequestFactoryTrait;

/**
 * @coversNothing
 */
#[CoversClass(RequestFactoryTrait::class)]
final class RequestFactoryTraitTest extends TestCase
{
    /**
     * @uses SonsOfPHP\Component\HttpMessage\Request
     * @uses SonsOfPHP\Component\HttpMessage\Uri
     */
    public function testCreateRequestWorksAsExpected(): void
    {
        $factory = $this->getMockForTrait(RequestFactoryTrait::class);

        $this->assertInstanceOf(RequestInterface::class, $factory->createRequest('GET', 'https://docs.sonsofphp.com'));
    }
}
