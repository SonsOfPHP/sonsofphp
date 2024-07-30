<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Enricher\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\NullMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\ChainMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(ChainMessageEnricherProvider::class)]
final class ChainMessageEnricherProviderTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $provider = new ChainMessageEnricherProvider();
        $this->assertInstanceOf(MessageEnricherProviderInterface::class, $provider);
    }

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
        $this->assertCount(1, iterator_to_array($enrichers));
    }
}
