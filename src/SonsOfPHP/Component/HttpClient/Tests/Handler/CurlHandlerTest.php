<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests\Handler;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\Handler\CurlHandler;
use SonsOfPHP\Component\HttpMessage\Request;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpClient\Handler\CurlHandler
 *
 * @internal
 */
final class CurlHandlerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, new CurlHandler());
    }
}
