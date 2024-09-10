<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Filesystem\Context;
use SonsOfPHP\Contract\Filesystem\ContextInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;
use stdClass;

/**
 * @uses \SonsOfPHP\Component\Filesystem\Context
 * @coversNothing
 */
#[CoversClass(Context::class)]
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

    public function testItCanParametersViaConstructor(): void
    {
        $context = new Context([
            'test' => true,
        ]);

        $this->assertTrue($context['test']);
    }

    public function testItCanSetValues(): void
    {
        $context = new Context();

        $this->assertArrayNotHasKey('testing', $context);

        $context['testing'] = true;
        $this->assertArrayHasKey('testing', $context);
        $this->assertTrue($context['testing']);
    }

    public function testItCanCheckIfKeysAreSet(): void
    {
        $context = new Context();

        $this->assertArrayNotHasKey('testing', $context);
        $context['testing'] = true;
        $this->assertArrayHasKey('testing', $context);

        unset($context['testing']);
        $this->assertArrayNotHasKey('testing', $context);
    }

    public function testItCanUnsetKeys(): void
    {
        $context = new Context();

        $this->assertArrayNotHasKey('testing', $context);
        unset($context['testing']);
        $this->assertArrayNotHasKey('testing', $context);

        $context['test'] = true;
        $this->assertArrayHasKey('test', $context);
        unset($context['test']);
        $this->assertArrayNotHasKey('test', $context);
    }

    public function testItCanGetValues(): void
    {
        $context = new Context();
        $this->assertNull($context['override']);

        $context['override'] = true;

        $this->assertTrue($context['override']);
    }

    public function testItCanReturnAnIteratorWhenItNeedsTo(): void
    {
        $context = new Context(['key' => 'value']);

        foreach ($context as $key => $value) {
            $this->assertSame('key', $key);
            $this->assertSame('value', $value);
        }
    }

    public function testItOnlySupportsStringsAsKeys(): void
    {
        $context = new Context();

        $this->expectException(FilesystemExceptionInterface::class);
        $context->offsetSet(new stdClass(), 'test');
    }

    public function testItOnlySupportsStringsAsKeysWhenCheckingIfExists(): void
    {
        $context = new Context();

        $this->expectException(FilesystemExceptionInterface::class);
        $context->offsetExists(new stdClass());
    }

    public function testItOnlySupportsStringsAsKeysWhenRemovingKeys(): void
    {
        $context = new Context();

        $this->expectException(FilesystemExceptionInterface::class);
        $context->offsetUnset(new stdClass());
    }

    public function testItOnlySupportsStringsAsKeysWhenGettingKey(): void
    {
        $context = new Context();

        $this->expectException(FilesystemExceptionInterface::class);
        $context->offsetGet(new stdClass());
    }
}
