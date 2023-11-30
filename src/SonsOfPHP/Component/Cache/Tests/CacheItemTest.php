<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Exception\InvalidArgumentException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\CacheItem
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 */
final class CacheItemTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $item = new CacheItem('testing');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    /**
     * @covers ::getKey
     */
    public function testGetKeyWorksAsExpected(): void
    {
        $item = new CacheItem('testing');

        $this->assertSame('testing', $item->getKey());
    }

    /**
     * @covers ::isHit
     */
    public function testDefaultValueForIsHit(): void
    {
        $item = new CacheItem('testing');

        $this->assertFalse($item->isHit());
    }

    /**
     * @covers ::get
     */
    public function testDefaultValueForGet(): void
    {
        $item = new CacheItem('testing');

        $this->assertNull($item->get());
    }

    /**
     * @covers ::set
     */
    public function testSetWorksAsExpected(): void
    {
        $item = new CacheItem('testing');
        $this->assertSame($item, $item->set('value'));
        $this->assertSame('value', $item->get());
    }

    /**
     * @covers ::expiresAt
     */
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

    /**
     * @covers ::expiresAt
     */
    public function testExpiresAtWorksAsExpectedWithDateTimeInterface(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertFalse(isset($itemAsArray["\0*\0expiry"]));

        $item->expiresAt(new \DateTimeImmutable('2020-04-20 04:20:00'));
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
        $this->assertSame(1587356400.0, $itemAsArray["\0*\0expiry"]);
    }

    /**
     * @covers ::expiresAfter
     */
    public function testExpiresAfterWorksAsExpectedWithIntegers(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertFalse(isset($itemAsArray["\0*\0expiry"]));

        $item->expiresAfter(3600);
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
    }

    /**
     * @covers ::expiresAfter
     */
    public function testExpiresAfterWorksAsExpectedWithDateInterval(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertFalse(isset($itemAsArray["\0*\0expiry"]));

        $item->expiresAfter(new \DateInterval('PT60S'));
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
    }

    /**
     * @covers ::expiresAfter
     */
    public function testExpiresAfterWorksAsExpectedWithNull(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertFalse(isset($itemAsArray["\0*\0expiry"]));

        $item->expiresAfter(null);
        $itemAsArray = (array) $item;
        $this->assertNull($itemAsArray["\0*\0expiry"]);
    }

    /**
     * @covers ::validateKey
     *
     * @dataProvider invalidKeysProvider
     */
    public function testValidateKeyWithInvalidValues(string $key): void
    {
        $this->expectException(InvalidArgumentException::class);
        $item = new CacheItem($key);
    }

    public static function invalidKeysProvider(): iterable
    {
        yield 'empty' => [''];

        yield 'space' => ['not allowed'];

        yield 'reserved' => ['contains@reserved}characters'];
    }
}
