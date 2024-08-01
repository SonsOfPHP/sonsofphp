<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionProperty;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\NullEnricher;
use SonsOfPHP\Component\Logger\Handler\AbstractHandler;
use SonsOfPHP\Component\Logger\Handler\NullHandler;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Logger;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\HandlerInterface;

#[CoversClass(Logger::class)]
#[UsesClass(Context::class)]
#[UsesClass(Level::class)]
#[UsesClass(Record::class)]
#[UsesClass(AbstractHandler::class)]
final class LoggerTest extends TestCase
{
    private MockObject $enricher;
    private MockObject $handler;

    public function setUp(): void
    {
        $this->enricher = $this->createMock(EnricherInterface::class);
        $this->handler = $this->createMock(HandlerInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $logger = new Logger();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testLogWithOneHandlersAndOneEnricher(): void
    {
        $this->enricher->expects($this->once())->method('__invoke')->willReturn($record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'enriched message',
            context: new Context(),
        ));

        $this->handler->expects($this->once())->method('handle');

        $logger = new Logger();
        $logger->addEnricher($this->enricher);
        $logger->addHandler($this->handler);

        $logger->log(level: 'debug', message: 'testing', context: []);
    }

    public function testLogWithNoHandlersAndOneEnricher(): void
    {
        $this->enricher->expects($this->once())->method('__invoke')->willReturn($record = new Record(
            channel: 'app',
            level: Level::Debug,
            message: 'enriched message',
            context: new Context(),
        ));

        $logger = new Logger();
        $logger->addEnricher($this->enricher);

        $logger->log(level: 'debug', message: 'testing', context: []);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testLogWithNoHandlersAndNoEnrichers(): void
    {
        $logger = new Logger();

        $logger->log(level: 'debug', message: 'testing', context: []);
    }

    public function testaddEnricher(): void
    {
        $logger = new Logger();
        $enrichers = new ReflectionProperty($logger, 'enrichers');
        $this->assertCount(0, $enrichers->getValue($logger));

        $logger->addEnricher(new NullEnricher());
        $this->assertCount(1, $enrichers->getValue($logger));

        $logger->addEnricher(new NullEnricher());
        $this->assertCount(2, $enrichers->getValue($logger));
    }

    public function testaddHandler(): void
    {
        $logger = new Logger();
        $handlers = new ReflectionProperty($logger, 'handlers');
        $this->assertCount(0, $handlers->getValue($logger));

        $logger->addHandler(new NullHandler());
        $this->assertCount(1, $handlers->getValue($logger));

        $logger->addHandler(new NullHandler());
        $this->assertCount(2, $handlers->getValue($logger));
    }
}
