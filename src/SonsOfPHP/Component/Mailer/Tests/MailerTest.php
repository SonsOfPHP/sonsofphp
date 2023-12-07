<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\Mailer;
use SonsOfPHP\Contract\Mailer\MailerInterface;
use SonsOfPHP\Contract\Mailer\TransportInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Mailer\Mailer
 *
 * @uses \SonsOfPHP\Component\Mailer\Mailer
 */
final class MailerTest extends TestCase
{
    private $transport;
    private $message;

    public function setUp(): void
    {
        $this->transport = $this->createMock(TransportInterface::class);
        $this->message = $this->createMock(MessageInterface::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $mailer = new Mailer($this->transport);

        $this->assertInstanceOf(MailerInterface::class, $mailer);
    }

    /**
     * @covers ::send
     */
    public function testSend(): void
    {
        $this->transport->expects($this->once())->method('send');

        $mailer = new Mailer($this->transport);

        $mailer->send($this->message);
    }
}
