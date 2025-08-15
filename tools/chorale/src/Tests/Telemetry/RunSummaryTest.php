<?php

declare(strict_types=1);

namespace Chorale\Tests\Telemetry;

use Chorale\Telemetry\RunSummary;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RunSummary::class)]
#[Group('unit')]
#[Small]
final class RunSummaryTest extends TestCase
{
    public function testIncIncrementsBucket(): void
    {
        $rs = new RunSummary();
        $rs->inc('new');
        self::assertSame(['new' => 1], $rs->all());
    }

    #[Test]
    public function testAllReturnsSortedKeys(): void
    {
        $rs = new RunSummary();
        $rs->inc('z');
        $rs->inc('a');
        $all = $rs->all();
        self::assertSame(['a','z'], array_keys($all));
    }
}
