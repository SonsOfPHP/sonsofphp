<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\ServerRequestFactory
 *
 * @internal
 */
final class ServerRequestFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, new ServerRequestFactory());
    }
}
