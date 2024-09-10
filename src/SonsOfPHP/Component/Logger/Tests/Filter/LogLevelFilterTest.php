<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Filter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Filter\LogLevelFilter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FilterInterface;

#[CoversClass(LogLevelFilter::class)]
#[UsesClass(Context::class)]
#[UsesClass(Level::class)]
#[UsesClass(Record::class)]
final class LogLevelFilterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $filter = new LogLevelFilter(Level::Debug);

        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    public function testConstructWillThrowInvalidArgumentException(): void
    {
        $this->expectException('InvalidArgumentException');
        new LogLevelFilter('app');
    }

    public function testIsLoggableIsTrueWhenLevelIsEqual(): void
    {
        $filter = new LogLevelFilter(Level::Debug);
        $record = new Record(
            channel: 'api',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );

        $this->assertTrue($filter->isLoggable($record));
    }

    public function testIsLoggableIsTrue(): void
    {
        $filter = new LogLevelFilter(Level::Debug);
        $record = new Record(
            channel: 'api',
            level: Level::Info,
            message: '',
            context: new Context(),
        );

        $this->assertTrue($filter->isLoggable($record));
    }

    public function testIsLoggableIsFalse(): void
    {
        $filter = new LogLevelFilter(Level::Alert);
        $record = new Record(
            channel: 'api',
            level: Level::Info,
            message: '',
            context: new Context(),
        );

        $this->assertFalse($filter->isLoggable($record));
    }
}
