<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Enricher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\NullMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricherInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\NullMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

#[CoversClass(MessageEnricher::class)]
#[UsesClass(NullMessageEnricherHandler::class)]
#[UsesClass(NullMessageEnricherProvider::class)]
#[UsesClass(AllMessageEnricherProvider::class)]
final class MessageEnricherTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $enricher = new MessageEnricher(new NullMessageEnricherProvider());
        $this->assertInstanceOf(MessageEnricherInterface::class, $enricher); // @phpstan-ignore-line
    }

    public function testItWillReturnMessageUntouchedWithNoHandlers(): void
    {
        $enricher        = new MessageEnricher(new NullMessageEnricherProvider());
        $message         = $this->createMock(MessageInterface::class);
        $enrichedMessage = $enricher->enrich($message);

        $this->assertSame($enrichedMessage, $message);
    }

    public function testItWillCanEnrichMessage(): void
    {
        $provider = new AllMessageEnricherProvider();
        $provider->register(new NullMessageEnricherHandler());

        $enricher        = new MessageEnricher($provider);
        $message         = $this->createMock(MessageInterface::class);
        $enrichedMessage = $enricher->enrich($message);

        $this->assertSame($enrichedMessage, $message);
    }
}
