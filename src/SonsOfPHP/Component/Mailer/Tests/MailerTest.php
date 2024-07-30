<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\Mailer;
use SonsOfPHP\Component\Mailer\MiddlewareHandler;
use SonsOfPHP\Component\Mailer\MiddlewareStack;
use SonsOfPHP\Contract\Mailer\MailerInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;

#[CoversClass(Mailer::class)]
#[UsesClass(MiddlewareHandler::class)]
#[UsesClass(MiddlewareStack::class)]
final class MailerTest extends TestCase
{
    private MockObject $transport;
    private MockObject $message;

    public function setUp(): void
    {
        $this->transport = $this->createMock(TransportInterface::class);
        $this->message = $this->createMock(MessageInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $mailer = new Mailer($this->transport);

        $this->assertInstanceOf(MailerInterface::class, $mailer);
    }

    public function testSend(): void
    {
        $this->transport->expects($this->once())->method('send');

        $mailer = new Mailer($this->transport);

        $mailer->send($this->message);
    }
}
