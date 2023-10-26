<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\ReadOnlyAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\ReadOnlyAdapter
 *
 * @internal
 */
final class ReadOnlyAdapterTest extends TestCase
{
    private AdapterInterface $adapter;

    public function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ReadOnlyAdapter($this->adapter);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
