<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message\Enricher\Handler;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler
 */
final class BlameableMessageEnricherHandlerTest extends TestCase
{
    private Security $security;

    protected function setUp(): void
    {
        $this->security = $this->createStub(Security::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $handler = new BlameableMessageEnricherHandler($this->security);

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    /**
     * @covers ::enrich
     */
    public function testEnrichWithoutUserWillNotEnrichMessage(): void
    {
        $handler = new BlameableMessageEnricherHandler($this->security);

        $message = $this->createMock(MessageInterface::class);
        $output = $handler->enrich($message);

        $this->assertSame($message, $output);
    }

    /**
     * @covers ::enrich
     */
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
