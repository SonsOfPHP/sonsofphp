<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Formatter\SimpleFormatter;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\FormatterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Formatter\SimpleFormatter
 *
 * @uses \SonsOfPHP\Component\Logger\Formatter\SimpleFormatter
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 * @uses \SonsOfPHP\Component\Logger\Level
 */
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

    /**
     * @covers ::formatMessage
     */
    public function testIsLoggableIsTrue(): void
    {
        $formatter = new SimpleFormatter();
        $record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'Example {key} Message',
            context: new Context(['key' => 'value']),
            datetime: new \DateTimeImmutable('2020-04-20T04:20:00+00:00'),
        );

        $this->assertSame("[2020-04-20T04:20:00+00:00] app.DEBUG: Example value Message {\"key\":\"value\"}\n", $formatter->formatMessage($record));
    }
}
