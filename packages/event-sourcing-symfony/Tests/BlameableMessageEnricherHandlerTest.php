<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Bridge\Symfony\BlameableMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class BlameableMessageEnricherHandlerTest extends TestCase
{
    private Security $security;

    protected function setUp(): void
    {
        $this->security = $this->createStub(Security::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $handler = new BlameableMessageEnricherHandler($this->security);

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    public function testEnrichWithoutUserWillNotEnrichMessage(): void
    {
        $handler = new BlameableMessageEnricherHandler($this->security);

        $message = $this->createMock(MessageInterface::class);
        $output = $handler->enrich($message);

        $this->assertSame($message, $output);
    }

    public function testEnrichWithUserWillEnrichTheMessage(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())->method('getUsername')->willReturn('satoshi');

        $this->security->expects($this->once())->method('getUser')->willReturn($user); // @phpstan-ignore-line

        $handler = new BlameableMessageEnricherHandler($this->security);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())
            ->method('withMetadata')
            ->with($this->callback(function ($metadata) {
                $this->assertArrayHasKey('__user', $metadata);
                $this->assertArrayHasKey('username', $metadata['__user']);
                $this->assertSame('satoshi', $metadata['__user']['username']);

                return true;
            }))
        ;

        $output = $handler->enrich($message);
    }
}
