<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Formatter;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Formatter\SimpleFormatter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FormatterInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Logger\Formatter\SimpleFormatter
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 * @uses \SonsOfPHP\Component\Logger\Level
 * @coversNothing
 */
#[CoversClass(SimpleFormatter::class)]
final class SimpleFormatterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $formatter = new SimpleFormatter();

        $this->assertInstanceOf(FormatterInterface::class, $formatter);
    }

    public function testIsLoggableIsTrue(): void
    {
        $formatter = new SimpleFormatter();
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'Example {key} Message',
            context: new Context(['key' => 'value']),
            datetime: new DateTimeImmutable('2020-04-20T04:20:00+00:00'),
        );

        $this->assertSame("[2020-04-20T04:20:00+00:00] app.DEBUG: Example value Message {\"key\":\"value\"}\n", $formatter->formatMessage($record));
    }
}
