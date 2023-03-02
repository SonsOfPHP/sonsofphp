<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Enricher\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\NullMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\ChainMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\ChainMessageEnricherProvider
 *
 * @internal
 */
final class ChainMessageEnricherProviderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $provider = new ChainMessageEnricherProvider();
        $this->assertInstanceOf(MessageEnricherProviderInterface::class, $provider);
    }

    /**
     * @covers ::__construct
     * @covers ::getEnrichersForMessage
     * @covers ::registerProvider
     */
    public function testRegisteredHandlerWillReturnWhenGettingEnrichers(): void
    {
        $providers = [
            $this->createMock(MessageEnricherProviderInterface::class),
            $this->createMock(MessageEnricherProviderInterface::class),
            $this->createMock(MessageEnricherProviderInterface::class),
        ];

        $providers[0]->expects($this->once())->method('getEnrichersForMessage')->willReturn([]);
        $providers[1]->expects($this->once())->method('getEnrichersForMessage')
            ->willReturn([new NullMessageEnricherHandler()]);
        $providers[2]->expects($this->once())->method('getEnrichersForMessage')->willReturn([]);

        $provider = new ChainMessageEnricherProvider($providers);

        $message = $this->createMock(MessageInterface::class);

        $enrichers = $provider->getEnrichersForMessage($message);
        $this->assertCount(1, $enrichers);
    }
}
