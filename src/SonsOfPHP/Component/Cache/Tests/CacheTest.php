<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Cache;
use Psr\SimpleCache\CacheInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\Cache
 *
 * @uses \SonsOfPHP\Component\Cache\Cache
 */
final class CacheTest extends TestCase
{
    private $adapter;

    public function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $cache = new Cache($this->adapter);

        $this->assertInstanceOf(CacheInterface::class, $cache);
    }
}
