<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\ScriptOwnerEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Enricher\ScriptOwnerEnricher
 *
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 */
final class ScriptOwnerEnricherTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new ScriptOwnerEnricher();

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    /**
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $enricher = new ScriptOwnerEnricher();
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(),
        ));

        $this->assertArrayHasKey('script_owner', $record->getContext());
    }
}
