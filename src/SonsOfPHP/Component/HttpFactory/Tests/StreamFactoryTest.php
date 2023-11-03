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

    /**
     * @covers ::createStream
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStream('just a test'));
    }

    /**
     * @covers ::createStreamFromResource
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamFromResourceWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStreamFromResource(fopen('php://memory', 'r')));
    }

    /**
     * @covers ::createStreamFromResource
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamFromResourceWillThrowExceptionWhenNotResource(): void
    {
        $factory = new StreamFactory();

        $this->expectException('InvalidArgumentException');
        $factory->createStreamFromResource('testing');
    }
}
