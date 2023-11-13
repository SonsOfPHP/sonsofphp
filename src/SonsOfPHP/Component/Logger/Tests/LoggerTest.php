<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Logger
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
}
