<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\UserIdEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

#[CoversClass(UserIdEnricher::class)]
#[UsesClass(Context::class)]
#[UsesClass(Record::class)]
final class UserIdEnricherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new UserIdEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    public function testInvoke(): void
    {
        $enricher = new UserIdEnricher();
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(),
        ));

        $this->assertArrayHasKey('user_id', $record->getContext());
    }
}
