<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpFactory\StreamFactory;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(StreamFactory::class)]
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
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStream('just a test'));
    }

    /**
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamFromResourceWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStreamFromResource(fopen('php://memory', 'r')));
    }

    /**
     * @uses \SonsOfPHP\Component\HttpMessage\Stream
     */
    public function testCreateStreamFromResourceWillThrowExceptionWhenNotResource(): void
    {
        $factory = new StreamFactory();

        $this->expectException('InvalidArgumentException');
        $factory->createStreamFromResource('testing');
    }
}
