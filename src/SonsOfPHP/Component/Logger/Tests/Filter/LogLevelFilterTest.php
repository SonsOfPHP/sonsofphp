<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Filter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Filter\LogLevelFilter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FilterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Filter\LogLevelFilter
 *
 * @uses \SonsOfPHP\Component\Logger\Filter\LogLevelFilter
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 * @uses \SonsOfPHP\Component\Logger\Level
 */
final class LogLevelFilterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $filter = new LogLevelFilter(Level::Debug);

        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWillThrowInvalidArgumentException(): void
    {
        $this->expectException('InvalidArgumentException');
        $filter = new LogLevelFilter('app');
    }

    /**
     * @covers ::isLoggable
     */
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

    /**
     * @covers ::isLoggable
     */
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

    /**
     * @covers ::isLoggable
     */
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
