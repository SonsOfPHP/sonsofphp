<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\NullAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\NullAdapter
 *
 * @internal
 */
final class NullAdapterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
