<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message\Enricher\Handler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @uses \SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\BlameableMessageEnricherHandler
 * @coversNothing
 */
#[CoversClass(BlameableMessageEnricherHandler::class)]
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
        $output  = $handler->enrich($message);

        $this->assertSame($message, $output);
    }

    public function testEnrichWithUserWillEnrichTheMessage(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())->method('getUserIdentifier')->willReturn('satoshi');

        $this->security->expects($this->once())->method('getUser')->willReturn($user); // @phpstan-ignore-line

        $handler = new BlameableMessageEnricherHandler($this->security);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())
            ->method('withMetadata')
            ->with($this->callback(function (array $metadata): bool {
                $this->assertArrayHasKey('__user', $metadata);
                $this->assertArrayHasKey('identifier', $metadata['__user']);
                $this->assertSame('satoshi', $metadata['__user']['identifier']);

                return true;
            }))
        ;

        $handler->enrich($message);
    }
}
