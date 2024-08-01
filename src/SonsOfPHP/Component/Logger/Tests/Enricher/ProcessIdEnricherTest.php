<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\ProcessIdEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

#[CoversClass(ProcessIdEnricher::class)]
#[UsesClass(Context::class)]
#[UsesClass(Record::class)]
final class ProcessIdEnricherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new ProcessIdEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    public function testInvoke(): void
    {
        $enricher = new ProcessIdEnricher();
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(),
        ));

        $this->assertArrayHasKey('process_id', $record->getContext());
    }
}
