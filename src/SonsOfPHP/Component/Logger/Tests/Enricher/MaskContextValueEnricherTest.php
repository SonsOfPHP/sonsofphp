<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests\Enricher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\MaskContextValueEnricher;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;

#[CoversClass(MaskContextValueEnricher::class)]
#[UsesClass(Context::class)]
#[UsesClass(Record::class)]
final class MaskContextValueEnricherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $enricher = new MaskContextValueEnricher('password');

        $this->assertInstanceOf(EnricherInterface::class, $enricher);
    }

    public function testInvokeWhenKeyIsNotAvailable(): void
    {
        $enricher = new MaskContextValueEnricher('password');
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(),
        ));

        $this->assertArrayNotHasKey('password', $record->getContext());
    }

    public function testInvokeWhenKeyIsArray(): void
    {
        $enricher = new MaskContextValueEnricher(['password', 'card_number']);
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(['password' => '123', 'card_number' => '4222-2222-2222-2222']),
        ));

        $this->assertArrayHasKey('password', $record->getContext());
        $this->assertArrayHasKey('card_number', $record->getContext());
        $this->assertNotSame('123', $record->getContext()['password']);
        $this->assertNotSame('4222-2222-2222-2222', $record->getContext()['card_number']);
    }

    public function testInvokeWhenKeyIsString(): void
    {
        $enricher = new MaskContextValueEnricher('password');
        $record = $enricher(new Record(
            channel: 'test',
            level: Level::Debug,
            message: '',
            context: new Context(['password' => 'superSecretBecauseItsPlainText']),
        ));

        $this->assertArrayHasKey('password', $record->getContext());
        $this->assertNotSame('superSecretBecauseItsPlainText', $record->getContext()['password']);
    }
}
