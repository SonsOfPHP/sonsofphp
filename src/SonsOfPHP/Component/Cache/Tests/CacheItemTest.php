<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Exception\InvalidArgumentException;

/**
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @coversNothing
 */
#[CoversClass(CacheItem::class)]
final class CacheItemTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $item = new CacheItem('testing');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    public function testGetKeyWorksAsExpected(): void
    {
        $item = new CacheItem('testing');

        $this->assertSame('testing', $item->getKey());
    }

    public function testDefaultValueForIsHit(): void
    {
        $item = new CacheItem('testing');

        $this->assertFalse($item->isHit());
    }

    public function testDefaultValueForGet(): void
    {
        $item = new CacheItem('testing');

        $this->assertNull($item->get());
    }

    public function testSetWorksAsExpected(): void
    {
        $item = new CacheItem('testing');
        $this->assertSame($item, $item->set('value'));
        $this->assertSame('value', $item->get());
    }

    public function testExpiresAtWorksAsExpectedWithNull(): void
    {
        $item = new CacheItem('testing');
        $item->expiresAfter(3600);

        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);

        $item->expiresAt(null);
        $itemAsArray = (array) $item;
        $this->assertNull($itemAsArray["\0*\0expiry"]);
    }

    public function testExpiresAtWorksAsExpectedWithDateTimeInterface(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertArrayNotHasKey('\0*\0expiry', $itemAsArray);

        $item->expiresAt(new DateTimeImmutable('2020-04-20 04:20:00'));
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
        $this->assertEqualsWithDelta(1587356400.0, $itemAsArray["\0*\0expiry"], PHP_FLOAT_EPSILON);
    }

    public function testExpiresAfterWorksAsExpectedWithIntegers(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertArrayNotHasKey('\0*\0expiry', $itemAsArray);

        $item->expiresAfter(3600);
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
    }

    public function testExpiresAfterWorksAsExpectedWithDateInterval(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertArrayNotHasKey('\0*\0expiry', $itemAsArray);

        $item->expiresAfter(new DateInterval('PT60S'));
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
    }

    public function testExpiresAfterWorksAsExpectedWithNull(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertArrayNotHasKey('\0*\0expiry', $itemAsArray);

        $item->expiresAfter(null);
        $itemAsArray = (array) $item;
        $this->assertNull($itemAsArray["\0*\0expiry"]);
    }


    #[DataProvider('invalidKeysProvider')]
    public function testValidateKeyWithInvalidValues(string $key): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CacheItem($key);
    }

    public static function invalidKeysProvider(): iterable
    {
        yield 'empty' => [''];

        yield 'space' => ['not allowed'];

        yield 'reserved' => ['contains@reserved}characters'];
    }
}
