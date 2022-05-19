<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Upcaster\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler\NullUpcasterHandler;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\EventTypeMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\MessageUpcasterProviderInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\EventTypeMessageUpcasterProvider
 */
final class EventTypeMessageUpcasterProviderTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();
        $this->assertInstanceOf(MessageUpcasterProviderInterface::class, $provider); // @phpstan-ignore-line
    }

    /**
     * @covers ::getUpcastersForEventData
     */
    public function testGetUpcastersForEventDataWillReturnEmptyArrayWhenNoHandlers(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();

        $eventData = [
            'metadata' => [
                Metadata::EVENT_TYPE => 'sons',
            ],
        ];
        $handlers = $provider->getUpcastersForEventData($eventData);

        $this->assertCount(0, $handlers);
    }

    /**
     * @covers ::getUpcastersForEventData
     */
    public function testGetUpcastersForEventDataWillThrowExceptionWhenCannotFindEventType(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();

        $this->expectException(EventSourcingException::class);
        $provider->getUpcastersForEventData([])->current(); // @phpstan-ignore-line
    }

    /**
     * @covers ::register
     * @covers ::getUpcastersForEventData
     */
    public function testGetUpcastersForEventData(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();
        $provider->register('sons', new NullUpcasterHandler());

        $eventData = [
            'metadata' => [
                Metadata::EVENT_TYPE => 'sons',
            ],
        ];
        $handlers = $provider->getUpcastersForEventData($eventData);
        $this->assertCount(1, $handlers);
    }
}
