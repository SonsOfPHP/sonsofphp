<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Enricher\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider
 *
 * @internal
 */
final class AllMessageEnricherProviderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $provider = new AllMessageEnricherProvider();
        $this->assertInstanceOf(MessageEnricherProviderInterface::class, $provider);
    }

    /**
     * @covers ::__construct
     * @covers ::getEnrichersForMessage
     * @covers ::register
     */
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
