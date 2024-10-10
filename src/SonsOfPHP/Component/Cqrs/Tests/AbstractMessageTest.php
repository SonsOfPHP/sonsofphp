<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use ArrayIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Contract\Cqrs\MessageInterface;
use stdClass;
use Stringable;

#[CoversClass(AbstractMessage::class)]
#[UsesClass(AbstractMessage::class)]
#[CoversNothing]
final class AbstractMessageTest extends TestCase
{
    public static function invalidValueProvider(): iterable
    {
        yield [new stdClass()];
        yield [new ArrayIterator()];
    }

    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $this->assertInstanceOf(MessageInterface::class, $msg);
    }

    public function testWithWhenKeyIsAnArrayThatContainsAStringableValue(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => new class implements Stringable {
                public function __toString(): string
                {
                    return 'value';
                }
            },
        ]);

        $this->assertSame('value', $msg->get('key'));
    }

    public function testWithWhenKeyIsAnArrayThatContainsAnInvalidValue(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => new stdClass(),
        ]);
    }

    public function testWithWhenKeyIsAnArrayAndValueIsNotNull(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => 'value',
        ], 'value');
    }

    public function testWithWhenKeyIsAnArray(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => 'value',
        ]);

        $this->assertSame('value', $msg->get('key'));
    }

    public function testWithWillReturnANewInstanceIfNewData(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('value', 'key');

        $this->assertNotSame($msg, $msg->with('key', 'value'));
        $this->assertSame($msg, $msg->with('value', 'key'));
    }


    #[DataProvider('invalidValueProvider')]
    public function testWithWillThrowInvalidArgumentExceptionWhenValueIsInvalid(mixed $value): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $this->expectException('InvalidArgumentException');
        $msg->with('key', $value);
    }

    public function testWithWorksAsExpectedWhenValueIsStringable(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $value = new class implements Stringable {
            public function __toString(): string
            {
                return 'value';
            }
        };

        $message = $msg->with('key', $value);

        $this->assertNotSame($msg, $message);
        $this->assertSame('value', $message->get('key'));
    }

    public function testGet(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertSame('value', $msg->get('key'));
    }

    public function testGetWillReturnAllWhenNoArgument(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->get());
    }

    public function testGetWillThrowExceptionWhenKeyNotFound(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->expectException('Exception');
        $msg->get('nope');
    }

    public function testSerializeMagicMethod(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->__serialize());
    }

    public function testUnserializeMagicMethod(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $message = unserialize(serialize($msg));

        $this->assertArrayHasKey('key', $message->get());
    }

    public function testSerialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertSame('{"key":"value"}', $msg->serialize());
    }

    public function testUnserialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');
        $msg->unserialize('{"key":"other value"}');

        $this->assertSame('other value', $msg->get('key'));
    }

    public function testjsonSerialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->jsonSerialize());
    }
}
