<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Enricher\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(AllMessageEnricherProvider::class)]
final class AllMessageEnricherProviderTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $provider = new AllMessageEnricherProvider();
        $this->assertInstanceOf(MessageEnricherProviderInterface::class, $provider);
    }

    public function testRegisteredHandlerWillReturnWhenGettingEnrichers(): void
    {
        $handler = $this->createMock(MessageEnricherHandlerInterface::class);
        $message = $this->createMock(MessageInterface::class);

        $provider = new AllMessageEnricherProvider();
        $provider->register($handler);

        $enrichers = $provider->getEnrichersForMessage($message);
        $this->assertCount(1, $enrichers);
        $this->assertSame($handler, $enrichers[0]);
    }
}
