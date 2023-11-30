<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Filter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Filter\ChannelFilter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FilterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Filter\ChannelFilter
 *
 * @uses \SonsOfPHP\Component\Logger\Filter\ChannelFilter
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 */
final class ChannelFilterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $filter = new ChannelFilter('app');

        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    /**
     * @covers ::isLoggable
     */
    public function testIsLoggableIsTrueWhenIsLoggableIsFalse(): void
    {
        $filter = new ChannelFilter('app', false);
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
    public function testIsLoggableIsFalseWhenIsLoggableIsFalse(): void
    {
        $filter = new ChannelFilter('app', false);
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );

        $this->assertFalse($filter->isLoggable($record));
    }

    /**
     * @covers ::isLoggable
     */
    public function testIsLoggableIsFalse(): void
    {
        $filter = new ChannelFilter('app');
        $record = new Record(
            channel: 'api',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );

        $this->assertFalse($filter->isLoggable($record));
    }

    /**
     * @covers ::isLoggable
     */
    public function testIsLoggableIsTrue(): void
    {
        $filter = new ChannelFilter('app');
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );

        $this->assertTrue($filter->isLoggable($record));
    }
}
