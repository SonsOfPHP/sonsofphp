<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Enricher\UserIdEnricher;
use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Context;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Enricher\UserIdEnricher
 *
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 */
final class UserIdEnricherTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new UserIdEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    /**
     * @covers ::__invoke
     */
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
