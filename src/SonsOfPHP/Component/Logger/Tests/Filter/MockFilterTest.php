<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Filter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Filter\MockFilter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FilterInterface;

#[CoversClass(MockFilter::class)]
#[UsesClass(Context::class)]
#[UsesClass(Record::class)]
final class MockFilterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $filter = new MockFilter();

        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    public function testIsLoggableIsTrue(): void
    {
        $filter = new MockFilter();
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );
        $this->assertTrue($filter->isLoggable($record));
    }

    public function testIsLoggableIsFalse(): void
    {
        $filter = new MockFilter(false);
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );
        $this->assertFalse($filter->isLoggable($record));
    }
}
