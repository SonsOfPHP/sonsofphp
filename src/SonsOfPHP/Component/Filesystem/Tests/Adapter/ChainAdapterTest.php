<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter
 *
 * @internal
 */
final class ChainAdapterTest extends TestCase
{
    private iterable $adapters = [];

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
