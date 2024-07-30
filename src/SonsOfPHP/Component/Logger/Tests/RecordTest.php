<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\RecordInterface;

#[CoversClass(Record::class)]
#[UsesClass(Context::class)]
final class RecordTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $this->assertInstanceOf(RecordInterface::class, $record);
    }

    public function testChannel(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $this->assertSame('app', $record->getChannel());
        $this->assertSame($record, $record->withChannel('app'));
        $this->assertNotSame($record, $record->withChannel('api'));
    }

    public function testLevel(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $this->assertSame(Level::Debug, $record->getLevel());
        $this->assertSame($record, $record->withLevel(Level::Debug));
        $this->assertNotSame($record, $record->withLevel(Level::Info));
    }

    public function testMessage(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $this->assertSame('testing', $record->getMessage());
        $this->assertSame($record, $record->withMessage('testing'));
        $this->assertNotSame($record, $record->withMessage('more test'));
    }

    public function testContext(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $this->assertSame([], $record->getContext()->all());
        $this->assertSame($record, $record->withContext(new Context()));
        $this->assertNotSame($record, $record->withContext(new Context(['key' => 'value'])));
    }

    public function testWithContextWhenArgumentIsArray(): void
    {
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'testing',
            context: new Context(),
        );

        $record = $record->withContext([
            'key' => 'value',
        ]);

        $this->assertArrayHasKey('key', $record->getContext());
        $this->assertSame('value', $record->getContext()['key']);
    }
}
