<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Contract\Cqrs\MessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cqrs\AbstractMessage
 *
 * @uses \SonsOfPHP\Component\Cqrs\AbstractMessage
 */
final class AbstractMessageTest extends TestCase
{
    public static function invalidValueProvider(): iterable
    {
        yield [new \stdClass()];
        yield [new \ArrayIterator()];
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $this->assertInstanceOf(MessageInterface::class, $msg);
    }

    /**
     * @covers ::with
     */
    public function testWithWhenKeyIsAnArrayThatContainsAStringableValue(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => new class () implements \Stringable {
                public function __toString(): string
                {
                    return 'value';
                }
            },
        ]);

        $this->assertSame('value', $msg->get('key'));
    }

    /**
     * @covers ::with
     */
    public function testWithWhenKeyIsAnArrayThatContainsAnInvalidValue(): void
    {
        $this->expectException('InvalidArgumentException');
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => new \stdClass(),
        ]);
    }

    /**
     * @covers ::with
     */
    public function testWithWhenKeyIsAnArrayAndValueIsNotNull(): void
    {
        $this->expectException('InvalidArgumentException');
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => 'value',
        ], 'value');
    }

    /**
     * @covers ::with
     */
    public function testWithWhenKeyIsAnArray(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with([
            'key' => 'value',
        ]);

        $this->assertSame('value', $msg->get('key'));
    }

    /**
     * @covers ::with
     */
    public function testWithWillReturnANewInstanceIfNewData(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('value', 'key');

        $this->assertNotSame($msg, $msg->with('key', 'value'));
        $this->assertSame($msg, $msg->with('value', 'key'));
    }

    /**
     * @covers ::with
     *
     * @dataProvider invalidValueProvider
     */
    public function testWithWillThrowInvalidArgumentExceptionWhenValueIsInvalid(mixed $value): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $this->expectException('InvalidArgumentException');
        $msg->with('key', $value);
    }

    /**
     * @covers ::with
     */
    public function testWithWorksAsExpectedWhenValueIsStringable(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class);

        $value = new class () implements \Stringable {
            public function __toString(): string
            {
                return 'value';
            }
        };

        $message = $msg->with('key', $value);

        $this->assertNotSame($msg, $message);
        $this->assertSame('value', $message->get('key'));
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertSame('value', $msg->get('key'));
    }

    /**
     * @covers ::get
     */
    public function testGetWillReturnAllWhenNoArgument(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->get());
    }

    /**
     * @covers ::get
     */
    public function testGetWillThrowExceptionWhenKeyNotFound(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->expectException('Exception');
        $msg->get('nope');
    }

    /**
     * @covers ::__serialize
     */
    public function testSerializeMagicMethod(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->__serialize());
    }

    /**
     * @covers ::__unserialize
     */
    public function testUnserializeMagicMethod(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $message = unserialize(serialize($msg));

        $this->assertArrayHasKey('key', $message->get());
    }

    /**
     * @covers ::serialize
     */
    public function testSerialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertSame('{"key":"value"}', $msg->serialize());
    }

    /**
     * @covers ::unserialize
     */
    public function testUnserialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');
        $msg->unserialize('{"key":"other value"}');

        $this->assertSame('other value', $msg->get('key'));
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testjsonSerialize(): void
    {
        $msg = $this->getMockForAbstractClass(AbstractMessage::class)->with('key', 'value');

        $this->assertArrayHasKey('key', $msg->jsonSerialize());
    }
}
