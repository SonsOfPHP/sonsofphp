<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpMessage\Message;

/**
 * @uses \SonsOfPHP\Component\HttpMessage\Message
 * @coversNothing
 */
#[CoversClass(Message::class)]
final class MessageTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(MessageInterface::class, new Message());
    }

    public function testItCanReturnCorrectDefaultProtocolVersion(): void
    {
        $message = new Message();

        $this->assertSame('1.1', $message->getProtocolVersion());
    }

    public function testItWithProtocolVersionWorksAsExpected(): void
    {
        $message = new Message();
        $other   = $message->withProtocolVersion('1.2');

        $this->assertNotSame($message, $other);
        $this->assertSame('1.1', $message->getProtocolVersion());
        $this->assertSame('1.2', $other->getProtocolVersion());
    }

    public function testItHasNoDefaultHeaders(): void
    {
        $msg = new Message();

        $this->assertCount(0, $msg->getHeaders());
    }

    public function testWithHeaderWorksAsExpectedWithStringValue(): void
    {
        $msg   = new Message();
        $other = $msg->withHeader('content-type', 'application/json');

        $this->assertNotSame($msg, $other);
        $this->assertCount(1, $other->getHeaders());
    }

    public function testWithHeaderWorksAsExpectedWithIterableValue(): void
    {
        $msg   = new Message();
        $other = $msg->withHeader('content-type', ['application/json']);

        $this->assertNotSame($msg, $other);
        $this->assertCount(1, $other->getHeaders());
    }

    public function testWithHeaderWorksAsExpectedWithInvalidHeaderName(): void
    {
        $msg = new Message();

        $this->expectException('InvalidArgumentException');
        $msg->withHeader('content type', 'does not matter');
    }

    public function testHasHeaderWorksAsExpected(): void
    {
        $msg = new Message();
        $this->assertFalse($msg->hasHeader('content-type'));
        $msg = $msg->withHeader('content-type', 'application/json');
        $this->assertTrue($msg->hasHeader('content-type'));
    }

    public function testGetHeaderWorksAsExpected(): void
    {
        $msg = new Message();
        $this->assertCount(0, $msg->getHeader('content-type'));
        $msg = $msg->withHeader('content-type', 'application/json');
        $this->assertCount(1, $msg->getHeader('content-type'));
        $this->assertSame('application/json', $msg->getHeader('content-type')[0]);
    }

    public function testGetHeaderLineWorksAsExpected(): void
    {
        $msg = new Message();
        $this->assertSame('', $msg->getHeaderLine('content-type'));

        $msg = $msg->withHeader('content-type', 'application/json');
        $this->assertSame('application/json', $msg->getHeaderLine('content-type'));
    }

    public function testWithAddedHeaderWorksAsExpected(): void
    {
        $message = (new Message())->withHeader('content-type', 'application/json');
        $this->assertCount(1, $message->getHeader('content-type'));

        $msg = $message->withAddedHeader('content-type', 'charset=utf-8');
        $this->assertNotSame($message, $msg);
        $this->assertCount(2, $msg->getHeader('content-type'));
    }

    public function testWithAddedHeaderWorksAsExpectedWhenInvalidHeaderName(): void
    {
        $message = new Message();
        $this->expectException('InvalidArgumentException');
        $message->withAddedHeader('content type', 'text/html');
    }

    public function testWithoutHeaderWorksAsExpected(): void
    {
        $message = (new Message())->withHeader('content-type', 'application/json');
        $this->assertCount(1, $message->getHeader('content-type'));

        $msg = $message->withoutHeader('content-type');
        $this->assertNotSame($message, $msg);
        $this->assertCount(0, $msg->getHeader('content-type'));
    }

    public function testWithBodyWorksAsExpected(): void
    {
        $message = new Message();
        $msg = $message->withBody($this->createMock(StreamInterface::class));

        $this->assertNotSame($message, $msg);
    }

    public function testGetBodyWorksAsExpected(): void
    {
        $msg = (new Message())->withBody($this->createMock(StreamInterface::class));

        $this->assertNotEmpty($msg->getBody());
    }

    public function testWithAddedHeaderWhenHeaderDoesNotExist(): void
    {
        $message = new Message();
        $this->assertCount(0, $message->getHeader('content-type'));

        $msg = $message->withAddedHeader('content-type', 'text/html');
        $this->assertNotSame($message, $msg);
        $this->assertCount(1, $msg->getHeader('content-type'));
    }

    public function testWithoutHeaderWorksAsExpectedWhenHeaderDoesNotExist(): void
    {
        $message = new Message();
        $this->assertCount(0, $message->getHeader('content-type'));

        $msg = $message->withoutHeader('content-type');
        $this->assertSame($message, $msg);
        $this->assertCount(0, $msg->getHeader('content-type'));
    }
}
