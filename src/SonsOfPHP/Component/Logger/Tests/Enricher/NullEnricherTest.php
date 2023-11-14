<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\NullEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Enricher\NullEnricher
 *
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 */
final class NullEnricherTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new NullEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $enricher = new NullEnricher();
        $record = new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(),
        );

        $this->assertSame($record, $enricher($record));
    }
}
