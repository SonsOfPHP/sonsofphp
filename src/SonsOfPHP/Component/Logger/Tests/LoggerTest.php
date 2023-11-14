<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SonsOfPHP\Component\Logger\Context;
use SonsOfPHP\Component\Logger\Enricher\NullEnricher;
use SonsOfPHP\Component\Logger\Handler\NullHandler;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Component\Logger\Logger;
use SonsOfPHP\Component\Logger\Record;
use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\HandlerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Logger
 *
 * @uses \SonsOfPHP\Component\Logger\Logger
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 * @uses \SonsOfPHP\Component\Logger\Level
 * @uses \SonsOfPHP\Component\Logger\Handler\AbstractHandler
 */
final class LoggerTest extends TestCase
{
    private $enricher;
    private $handler;

    public function setUp(): void
    {
        $this->enricher = $this->createMock(EnricherInterface::class);
        $this->handler = $this->createMock(HandlerInterface::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $logger = new Logger();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    /**
     * @covers ::log
     */
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

    /**
     * @covers ::log
     */
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
     * @covers ::log
     * @doesNotPerformAssertions
     */
    public function testLogWithNoHandlersAndNoEnrichers(): void
    {
        $logger = new Logger();

        $logger->log(level: 'debug', message: 'testing', context: []);
    }

    /**
     * @covers ::addEnricher
     */
    public function testaddEnricher(): void
    {
        $logger = new Logger();
        $enrichers = new \ReflectionProperty($logger, 'enrichers');
        $this->assertCount(0, $enrichers->getValue($logger));

        $logger->addEnricher(new NullEnricher());
        $this->assertCount(1, $enrichers->getValue($logger));

        $logger->addEnricher(new NullEnricher());
        $this->assertCount(2, $enrichers->getValue($logger));
    }

    /**
     * @covers ::addHandler
     */
    public function testaddHandler(): void
    {
        $logger = new Logger();
        $handlers = new \ReflectionProperty($logger, 'handlers');
        $this->assertCount(0, $handlers->getValue($logger));

        $logger->addHandler(new NullHandler());
        $this->assertCount(1, $handlers->getValue($logger));

        $logger->addHandler(new NullHandler());
        $this->assertCount(2, $handlers->getValue($logger));
    }
}
