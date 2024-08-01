<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\NullEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

#[CoversClass(NullEnricher::class)]
#[UsesClass(Context::class)]
#[UsesClass(Record::class)]
final class NullEnricherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new NullEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

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
