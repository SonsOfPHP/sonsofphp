<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\RequestFactory
 *
 * @internal
 */
final class RequestFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(RequestFactoryInterface::class, new RequestFactory());
    }

    /**
     * @covers ::createRequest
     */
    public function testCreateRequestWorksAsExpected(): void
    {
        $factory = new RequestFactory();

        $this->assertInstanceOf(RequestInterface::class, $factory->createRequest('GET', 'https://docs.sonsofphp.com'));
    }
}
