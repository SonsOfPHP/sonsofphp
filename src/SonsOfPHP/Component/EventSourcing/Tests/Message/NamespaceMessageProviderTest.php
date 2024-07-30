<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\NamespaceMessageProvider;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;
use stdClass;

/**
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\NamespaceMessageProvider
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 * @coversNothing
 */
#[CoversClass(NamespaceMessageProvider::class)]
final class NamespaceMessageProviderTest extends TestCase
{
    private string $namespace;

    protected function setUp(): void
    {
        $this->namespace = 'SonsOfPHP\\Component\\EventSourcing\\Tests';
    }

    public function testItHasTheRightInterface(): void
    {
        $provider = new NamespaceMessageProvider($this->namespace);

        $this->assertInstanceOf(MessageProviderInterface::class, $provider);
    }

    public function testGetEventTypeForMessageWithMessageClass(): void
    {
        $provider = new NamespaceMessageProvider($this->namespace);

        $eventType = $provider->getEventTypeForMessage(FakeSerializableMessage::class);

        $this->assertSame('FakeSerializableMessage', $eventType);
    }

    public function testGetEventTypeForMessageWithSubnamespaces(): void
    {
        $provider = new NamespaceMessageProvider('SonsOfPHP\\Component\\EventSourcing');

        $eventType = $provider->getEventTypeForMessage(FakeSerializableMessage::class);

        $this->assertSame('Tests\FakeSerializableMessage', $eventType);
    }

    public function testGetEventTypeForMessageWithUnknownMessageClassThrowsException(): void
    {
        $provider = new NamespaceMessageProvider('Tests');

        $this->expectException(EventSourcingException::class);
        $provider->getEventTypeForMessage(FakeSerializableMessage::class);
    }

    public function testGetEventTypeForMessageWithMessageObject(): void
    {
        $provider = new NamespaceMessageProvider($this->namespace);

        $eventType = $provider->getEventTypeForMessage(FakeSerializableMessage::new());

        $this->assertSame('FakeSerializableMessage', $eventType);
    }

    public function testGetEventTypeForMessageWithUnknownMessageObjectThrowsException(): void
    {
        $provider = new NamespaceMessageProvider('Tests');

        $this->expectException(EventSourcingException::class);
        $provider->getEventTypeForMessage(FakeSerializableMessage::new());
    }

    public function testGetEventTypeForMessageWithObjectThatDoesNotImplementInterface(): void
    {
        $provider = new NamespaceMessageProvider('Tests');

        $this->expectException(EventSourcingException::class);
        $provider->getEventTypeForMessage(new stdClass());
    }

    public function testGetMessageClassForEventType(): void
    {
        $provider = new NamespaceMessageProvider($this->namespace);

        $messageClass = $provider->getMessageClassForEventType('FakeSerializableMessage');

        $this->assertSame(FakeSerializableMessage::class, $messageClass);
    }

    public function testGetMessageClassForEventTypeWithUnknownEventTypeThrowsException(): void
    {
        $provider = new NamespaceMessageProvider($this->namespace);

        $this->expectException(EventSourcingException::class);
        $provider->getMessageClassForEventType('test');
    }

    public function testGetMessageClassForEventTypeWithSubnamespaces(): void
    {
        $provider = new NamespaceMessageProvider('SonsOfPHP\\Component\\EventSourcing');

        $this->assertSame(FakeSerializableMessage::class, $provider->getMessageClassForEventType('Tests\FakeSerializableMessage'));
    }
}
