<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Upcaster;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler\NullUpcasterHandler;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcasterInterface;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\EventTypeMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\NullMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Metadata;

#[CoversClass(MessageUpcaster::class)]
#[UsesClass(NullUpcasterHandler::class)]
#[UsesClass(EventTypeMessageUpcasterProvider::class)]
#[UsesClass(NullMessageUpcasterProvider::class)]
final class MessageUpcasterTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $upcaster = new MessageUpcaster(new NullMessageUpcasterProvider());
        $this->assertInstanceOf(MessageUpcasterInterface::class, $upcaster); // @phpstan-ignore-line
    }

    public function testItWillReturnDataUntouchedWithNoHandlers(): void
    {
        $upcaster = new MessageUpcaster(new NullMessageUpcasterProvider());

        $eventData = [
            'example' => 'test',
        ];

        $upcastedData = $upcaster->upcast($eventData);

        $this->assertSame($eventData, $upcastedData);
    }

    public function testItWillUpcastEventData(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();
        $provider->register('sons', new NullUpcasterHandler());

        $eventData = [
            'metadata' => [
                Metadata::EVENT_TYPE => 'sons',
            ],
        ];
        $upcaster     = new MessageUpcaster($provider);
        $upcastedData = $upcaster->upcast($eventData);

        $this->assertSame($eventData, $upcastedData);
    }
}
