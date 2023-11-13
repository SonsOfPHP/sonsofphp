<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Logger;
use SonsOfPHP\Component\Logger\Handler\NullHandler;
use SonsOfPHP\Component\Logger\Enricher\NullEnricher;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Logger
 *
 * @uses \SonsOfPHP\Component\Logger\Logger
 * @uses \SonsOfPHP\Component\Logger\Context
 * @uses \SonsOfPHP\Component\Logger\Record
 */
final class LoggerTest extends TestCase
{
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
     * @doesNotPerformAssertions
     */
    public function testLogWithNoHandlersAndNoEnrichers(): void
    {
        $logger = new Logger();

        $logger->log(level: 'debug', message: 'testing', context: []);
    }

    /**
     * @covers ::pushEnricher
     */
    public function testPushEnricher(): void
    {
        $logger = new Logger();
        $enrichers = new \ReflectionProperty($logger, 'enrichers');
        $this->assertCount(0, $enrichers->getValue($logger));

        $logger->pushEnricher(new NullEnricher());
        $this->assertCount(1, $enrichers->getValue($logger));

        $logger->pushEnricher(new NullEnricher());
        $this->assertCount(2, $enrichers->getValue($logger));
    }

    /**
     * @covers ::pushHandler
     */
    public function testPushHandler(): void
    {
        $logger = new Logger();
        $handlers = new \ReflectionProperty($logger, 'handlers');
        $this->assertCount(0, $handlers->getValue($logger));

        $logger->pushHandler(new NullHandler());
        $this->assertCount(1, $handlers->getValue($logger));

        $logger->pushHandler(new NullHandler());
        $this->assertCount(2, $handlers->getValue($logger));
    }
}
