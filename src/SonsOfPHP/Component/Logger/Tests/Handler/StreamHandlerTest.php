<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Handler;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Handler\AbstractHandler;
use SonsOfPHP\Component\Logger\Handler\StreamHandler;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\HandlerInterface;

#[CoversClass(StreamHandler::class)]
#[UsesClass(Context::class)]
#[UsesClass(AbstractHandler::class)]
#[UsesClass(Record::class)]
final class StreamHandlerTest extends TestCase
{
    public function setUp(): void
    {
        if (file_exists('/tmp/testing.log')) {
            unlink('/tmp/testing.log');
        }
    }

    public function testItHasTheCorrectInterface(): void
    {
        $handler = new StreamHandler(fopen('/tmp/testing.log', 'a'));

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    public function testItCanWrite(): void
    {
        $handler = new StreamHandler(fopen('/tmp/testing.log', 'a'));
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'Example {key} Message',
            context: new Context(['key' => 'value']),
            datetime: new DateTimeImmutable('2020-04-20T04:20:00+00:00'),
        );

        $this->assertSame('', file_get_contents('/tmp/testing.log'));
        $handler->handle($record);
        $this->assertNotSame('', file_get_contents('/tmp/testing.log'));

        unset($handler);
    }
}
