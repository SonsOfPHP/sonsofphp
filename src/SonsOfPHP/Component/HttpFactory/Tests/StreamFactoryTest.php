<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\StreamFactory;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\StreamFactory
 *
 * @internal
 */
final class StreamFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(StreamFactoryInterface::class, new StreamFactory());
    }
}
