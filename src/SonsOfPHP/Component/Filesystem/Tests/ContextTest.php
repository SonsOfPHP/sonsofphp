<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Context;
use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Filesystem\Context
 *
 * @uses \SonsOfPHP\Component\Filesystem\Context
 */
final class ContextTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $context = new Context();

        $this->assertInstanceOf(ContextInterface::class, $context);
    }

    /**
     * @covers ::__construct
     */
    public function testItCanParametersViaConstructor(): void
    {
        $context = new Context([
            'test' => true,
        ]);

        $this->assertTrue($context['test']);
    }

    /**
     * @covers ::offsetSet
     */
    public function testItCanSetValues(): void
    {
        $context = new Context();

        $this->assertFalse(isset($context['testing']));

        $context['testing'] = true;
        $this->assertTrue(isset($context['testing']));
        $this->assertTrue($context['testing']);
    }

    /**
     * @covers ::offsetExists
     */
    public function testItCanCheckIfKeysAreSet(): void
    {
        $context = new Context();

        $this->assertFalse(isset($context['testing']));
        $context['testing'] = true;
        $this->assertTrue(isset($context['testing']));

        unset($context['testing']);
        $this->assertFalse(isset($context['testing']));
    }

    /**
     * @covers ::offsetUnset
     */
    public function testItCanUnsetKeys(): void
    {
        $context = new Context();

        $this->assertFalse(isset($context['testing']));
        unset($context['testing']);
        $this->assertFalse(isset($context['testing']));

        $context['test'] = true;
        $this->assertTrue(isset($context['test']));
        unset($context['test']);
        $this->assertFalse(isset($context['test']));
    }

    /**
     * @covers ::offsetGet
     */
    public function testItCanGetValues(): void
    {
        $context = new Context();
        $this->assertNull($context['override']);

        $context['override'] = true;

        $this->assertTrue($context['override']);
    }

    /**
     * @covers ::getIterator
     */
    public function testItCanReturnAnIteratorWhenItNeedsTo(): void
    {
        $context = new Context(['key' => 'value']);

        foreach ($context as $key => $value) {
            $this->assertSame('key', $key);
            $this->assertSame('value', $value);
        }
    }

    /**
     * @covers ::offsetSet
     */
    public function testItOnlySupportsStringsAsKeys(): void
    {
        $context = new Context();

        $this->expectException(FilesystemException::class);
        $context->offsetSet(new \stdClass(), 'test');
    }

    /**
     * @covers ::offsetExists
     */
    public function testItOnlySupportsStringsAsKeysWhenCheckingIfExists(): void
    {
        $context = new Context();

        $this->expectException(FilesystemException::class);
        $context->offsetExists(new \stdClass());
    }

    /**
     * @covers ::offsetUnset
     */
    public function testItOnlySupportsStringsAsKeysWhenRemovingKeys(): void
    {
        $context = new Context();

        $this->expectException(FilesystemException::class);
        $context->offsetUnset(new \stdClass());
    }

    /**
     * @covers ::offsetGet
     */
    public function testItOnlySupportsStringsAsKeysWhenGettingKey(): void
    {
        $context = new Context();

        $this->expectException(FilesystemException::class);
        $context->offsetGet(new \stdClass());
    }
}
