<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Configuration;
use SonsOfPHP\Component\EventSourcing\ConfigurationInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Configuration
 */
final class ConfigurationTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ConfigurationInterface::class, new Configuration());
    }
}
