<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Record
 *
 * @uses \SonsOfPHP\Component\Logger\Record
 * @uses \SonsOfPHP\Component\Logger\Level
 * @uses \SonsOfPHP\Component\Logger\Context
 */
final class RecordTest extends TestCase
{
    /**
     * @covers ::__construct
     */
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

    /**
     * @covers ::getChannel
     * @covers ::withChannel
     */
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

    /**
     * @covers ::getLevel
     * @covers ::withLevel
     */
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

    /**
     * @covers ::getMessage
     * @covers ::withMessage
     */
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

    /**
     * @covers ::getContext
     * @covers ::withContext
     */
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

    /**
     * @covers ::withContext
     */
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
