<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Upcaster\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler\NullUpcasterHandler;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\EventTypeMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\MessageUpcasterProviderInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

final class EventTypeMessageUpcasterProviderTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();
        $this->assertInstanceOf(MessageUpcasterProviderInterface::class, $provider); // @phpstan-ignore-line
    }

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

    public function testGetUpcastersForEventDataWillThrowExceptionWhenCannotFindEventType(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();

        $this->expectException(EventSourcingException::class);
        $provider->getUpcastersForEventData([])->current(); // @phpstan-ignore-line
    }

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
