<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\Message;
use SonsOfPHP\Contract\Mailer\MessageInterface;

/**
 * @uses \SonsOfPHP\Component\Mailer\Message
 * @coversNothing
 */
#[CoversClass(Message::class)]
final class MessageTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $message = new Message();

        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testSetBody(): void
    {
        $message = new Message();
        $message->setBody('body');
        $this->assertSame('body', $message->getBody());
    }

    public function testGetBody(): void
    {
        $message = new Message();
        $this->assertNull($message->getBody());

        $message->setBody('body');
        $this->assertSame('body', $message->getBody());
    }

    public function testGetHeadersWhenEmpty(): void
    {
        $message = new Message();
        $this->assertCount(0, $message->getHeaders());
    }

    public function testGetHeader(): void
    {
        $message = new Message();
        $this->assertNull($message->getHeader('to'));

        $message->addHeader('to', 'joshua@sonsofphp.com');
        $this->assertSame('joshua@sonsofphp.com', $message->getHeader('to'));
    }

    public function testHasHeader(): void
    {
        $message = new Message();
        $this->assertFalse($message->hasHeader('to'));

        $message->addHeader('to', 'joshua@sonsofphp.com');
        $this->assertTrue($message->hasHeader('TO'));
    }

    public function testAddHeader(): void
    {
        $message = new Message();

        $message->addHeader('To', 'joshua@sonsofphp.com');
        $this->assertSame('joshua@sonsofphp.com', $message->getHeader('to'));

        $message->addHeader('To', 'joshua.estes@sonsofphp.com');
        $this->assertSame('joshua@sonsofphp.com, joshua.estes@sonsofphp.com', $message->getHeader('to'));
    }
}
