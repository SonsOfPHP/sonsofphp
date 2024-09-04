<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert\Tests;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Assert\Assert;
use SonsOfPHP\Component\Assert\InvalidArgumentException;
use stdClass;

#[CoversClass(Assert::class)]
final class AssertTest extends TestCase
{
    public static function validStringProvider(): Generator
    {
        yield ['test'];
    }

    public static function validIntProvider(): Generator
    {
        yield [1];
        yield [0];
        yield [-1];
    }

    public static function validFloatProvider(): Generator
    {
        yield [1.0];
        yield [0.0];
        yield [-1.0];
    }

    public static function validNumericProvider(): Generator
    {
        yield from static::validFloatProvider();
        yield from static::validIntProvider();
        yield ['42'];
    }

    public static function validBoolProvider(): Generator
    {
        yield [true];
        yield [false];
    }

    public static function validScalarProvider(): Generator
    {
        yield from static::validStringProvider();
        yield from static::validBoolProvider();
        yield from static::validFloatProvider();
        yield from static::validIntProvider();
    }

    public static function validObjectProvider(): Generator
    {
        yield [new stdClass()];
    }

    public static function validCallableProvider(): Generator
    {
        yield [function (): void {}];
    }

    public static function validArrayProvider(): Generator
    {
        yield [[]];
    }

    public static function validNullProvider(): Generator
    {
        yield [null];
    }

    #[DataProvider('validStringProvider')]
    public function testItCanIdentifyString(mixed $value): void
    {
        $this->assertTrue(Assert::string($value));
    }

    #[DataProvider('validIntProvider')]
    #[DataProvider('validFloatProvider')]
    public function testItCanIdentifyNotString(mixed $value): void
    {
        $this->assertTrue(Assert::notString($value));
    }

    public function testItCanIdentifyNullOrString(): void
    {
        $this->assertTrue(Assert::nullOrString(null));
        $this->assertTrue(Assert::nullOrString('Sons of PHP'));
    }

    public function testItCanIdentifyAllString(): void
    {
        $this->assertTrue(Assert::allString(['Sons', 'of', 'PHP']));
    }

    #[DataProvider('validIntProvider')]
    #[DataProvider('validFloatProvider')]
    #[DataProvider('validBoolProvider')]
    #[DataProvider('validObjectProvider')]
    #[DataProvider('validCallableProvider')]
    #[DataProvider('validArrayProvider')]
    public function testItWillThrowExceptionForStringWithInvalidString(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_STRING);
        Assert::string($value);
    }

    #[DataProvider('validIntProvider')]
    public function testItCanIdentifyInt(mixed $value): void
    {
        $this->assertTrue(Assert::int($value));
    }

    #[DataProvider('validStringProvider')]
    #[DataProvider('validFloatProvider')]
    public function testItWillThrowExceptionForIntWithInvalidInt(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_INTEGER);
        Assert::int($value);
    }

    #[DataProvider('validFloatProvider')]
    public function testItCanIdentifyFloat(mixed $value): void
    {
        $this->assertTrue(Assert::float($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForFloatWithFloat(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_FLOAT);
        Assert::float($value);
    }

    #[DataProvider('validNumericProvider')]
    public function testItCanIdentifyNumeric(mixed $value): void
    {
        $this->assertTrue(Assert::numeric($value));
    }

    #[DataProvider('validArrayProvider')]
    public function testItWillThrowExceptionForNumericWithNumeric(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_NUMERIC);
        Assert::numeric($value);
    }

    #[DataProvider('validBoolProvider')]
    public function testItCanIdentifyBool(mixed $value): void
    {
        $this->assertTrue(Assert::bool($value));
    }

    #[DataProvider('validArrayProvider')]
    public function testItWillThrowExceptionForBoolWithBool(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_BOOLEAN);
        Assert::bool($value);
    }

    #[DataProvider('validScalarProvider')]
    public function testItCanIdentifyScalar(mixed $value): void
    {
        $this->assertTrue(Assert::scalar($value));
    }

    #[DataProvider('validArrayProvider')]
    #[DataProvider('validObjectProvider')]
    public function testItWillThrowExceptionForScalarWithScalar(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_SCALAR);
        Assert::scalar($value);
    }

    #[DataProvider('validObjectProvider')]
    public function testItCanIdentifyObject(mixed $value): void
    {
        $this->assertTrue(Assert::object($value));
    }

    #[DataProvider('validArrayProvider')]
    public function testItWillThrowExceptionForObjectWithObject(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_OBJECT);
        Assert::object($value);
    }

    #[DataProvider('validCallableProvider')]
    public function testItCanIdentifyCallable(mixed $value): void
    {
        $this->assertTrue(Assert::callable($value));
    }

    #[DataProvider('validArrayProvider')]
    public function testItWillThrowExceptionForCallableWithCallable(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_CALLABLE);
        Assert::callable($value);
    }

    #[DataProvider('validArrayProvider')]
    public function testItCanIdentifyArray(mixed $value): void
    {
        $this->assertTrue(Assert::array($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForArrayWithArray(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_ARRAY);
        Assert::array($value);
    }
}
