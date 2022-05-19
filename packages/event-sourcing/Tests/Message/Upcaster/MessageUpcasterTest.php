<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Upcaster;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler\NullUpcasterHandler;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcasterInterface;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\EventTypeMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\NullMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster
 */
final class MessageUpcasterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $upcaster = new MessageUpcaster(new NullMessageUpcasterProvider());
        $this->assertInstanceOf(MessageUpcasterInterface::class, $upcaster); // @phpstan-ignore-line
    }

    /**
     * @covers ::upcast
     */
    public function testItWillReturnDataUntouchedWithNoHandlers(): void
    {
        $upcaster = new MessageUpcaster(new NullMessageUpcasterProvider());

        $eventData = [
            'example' => 'test',
        ];

        $upcastedData = $upcaster->upcast($eventData);

        $this->assertSame($eventData, $upcastedData);
    }

    /**
     * @covers ::upcast
     */
    public function testItWillUpcastEventData(): void
    {
        $provider = new EventTypeMessageUpcasterProvider();
        $provider->register('sons', new NullUpcasterHandler());
        $eventData = [
            'metadata' => [
                Metadata::EVENT_TYPE => 'sons',
            ],
        ];
        $upcaster = new MessageUpcaster($provider);
        $upcastedData = $upcaster->upcast($eventData);

        $this->assertSame($eventData, $upcastedData);
    }
}
