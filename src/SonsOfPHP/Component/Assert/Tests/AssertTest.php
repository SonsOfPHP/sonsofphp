<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert\Tests;

use BadMethodCallException;
use DateTime;
use DateTimeImmutable;
use Exception;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SonsOfPHP\Component\Assert\Assert;
use SonsOfPHP\Component\Assert\InvalidArgumentException;
use stdClass;
use Stringable;

#[CoversClass(Assert::class)]
final class AssertTest extends TestCase
{
    protected function tearDown(): void
    {
        // Reset the class to the defaults after each test
        Assert::setExceptionClass(InvalidArgumentException::class);
        Assert::enable();
    }

    public static function valueToStringProvider(): Generator
    {
        // expected, value
        yield ['null', null];
        yield ['array', []];
        yield ['testing', 'testing'];
        yield ['false', false];
        yield ['true', true];
        yield ['0', 0];
        yield ['0.000000', 0.0];
        yield ['stdClass', new stdClass()];
        yield ['DateTime: 2024-01-01T00:00:00+00:00', new DateTime('2024-01-01 00:00:00')];
        yield ['DateTimeImmutable: 2024-01-01T00:00:00+00:00', new DateTimeImmutable('2024-01-01 00:00:00')];
        yield ['Sons of PHP', new class implements Stringable {
            public function __toString(): string
            {
                return 'Sons of PHP';
            }
        }];
        yield ['resource (stream)', fopen('php://memory', 'w')];
    }

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

    public static function validEmptyProvider(): Generator
    {
        yield from self::validNullProvider();
        yield [''];
    }

    public static function validResourceProvider(): Generator
    {
        yield [fopen('php://memory', 'w')];
    }

    public static function validNullProvider(): Generator
    {
        yield [null];
    }

    public static function validTrueProvider(): Generator
    {
        yield [true];
    }

    public static function validFalseProvider(): Generator
    {
        yield [false];
    }

    public static function validEqProvider(): Generator
    {
        yield ['value', 'value'];
    }

    public static function invalidEqProvider(): Generator
    {
        yield ['value', ''];
    }

    public static function validSameProvider(): Generator
    {
        yield ['value', 'value'];
    }

    public static function invalidSameProvider(): Generator
    {
        yield ['value', ''];
    }

    #[DataProvider('validStringProvider')]
    public function testItCanIdentifyString(mixed $value): void
    {
        $this->assertTrue(Assert::string($value));
    }

    public function testItHasTheCorrectDefaultExceptionClass(): void
    {
        $this->assertSame(InvalidArgumentException::class, Assert::getExceptionClass());
    }

    public function testItsExceptionClassIsMutable(): void
    {
        Assert::setExceptionClass('Exception');
        $this->assertSame(Exception::class, Assert::getExceptionClass());
    }

    #[DataProvider('valueToStringProvider')]
    public function testItCanConvertValueToString(mixed $expected, mixed $value): void
    {
        $method = new ReflectionMethod(Assert::class, 'valueToString');
        $this->assertSame($expected, $method->invoke(null, $value));
    }

    #[DataProvider('validIntProvider')]
    #[DataProvider('validFloatProvider')]
    public function testItCanIdentifyNotString(mixed $value): void
    {
        $this->assertTrue(Assert::notString($value));
    }

    public function testItWillThrowExceptionForNotStringWhenStringPassedIn(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::notString('Sons of PHP');
    }

    public function testItCanIdentifyNullOrString(): void
    {
        $this->assertTrue(Assert::nullOrString(null));
        $this->assertTrue(Assert::nullOrString('Sons of PHP'));
    }

    public function testItWillThrowExceptionForNullOrStringWhenNotNullOrString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::nullOrString(42);
    }

    public function testItCanIdentifyAllString(): void
    {
        $this->assertTrue(Assert::allString(['Sons', 'of', 'PHP']));
    }

    public function testItWillThrowExceptionForAllStringWhenNotAllStrings(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::allString(['duck', 'duck', 'goose', 42]);
    }

    public function testItWillThrowBadMethodCallExceptionWhenStaticMethodIsInvalid(): void
    {
        $this->expectException(BadMethodCallException::class);
        Assert::badMethod();
    }

    public function testItWillThrowBadMethodCallExceptionWhenStaticMethodIsInvalidButStartsWithKeyword(): void
    {
        $this->expectException(BadMethodCallException::class);
        Assert::notAMethod();
    }

    public function testItWillNotThrowExceptionWhenDisabled(): void
    {
        Assert::disable();
        $this->assertFalse(Assert::string(42));

        Assert::enable();
        $this->expectException(InvalidArgumentException::class);
        Assert::string(42);
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

    #[DataProvider('validEmptyProvider')]
    public function testItCanIdentifyEmpty(mixed $value): void
    {
        $this->assertTrue(Assert::empty($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForEmptyWithEmpty(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::empty($value);
    }

    #[DataProvider('validNullProvider')]
    public function testItCanIdentifyNull(mixed $value): void
    {
        $this->assertTrue(Assert::null($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForNullWithNull(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::null($value);
    }

    #[DataProvider('validTrueProvider')]
    public function testItCanIdentifyTrue(mixed $value): void
    {
        $this->assertTrue(Assert::true($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForTrueWithTrue(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::true($value);
    }

    #[DataProvider('validFalseProvider')]
    public function testItCanIdentifyFalse(mixed $value): void
    {
        $this->assertTrue(Assert::false($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForFalseWithFalse(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::false($value);
    }

    #[DataProvider('validEqProvider')]
    public function testItCanIdentifyEq(mixed $value, mixed $value2): void
    {
        $this->assertTrue(Assert::eq($value, $value2));
    }

    #[DataProvider('invalidEqProvider')]
    public function testItWillThrowExceptionForEqWithEq(mixed $value, mixed $value2): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::eq($value, $value2);
    }

    #[DataProvider('validSameProvider')]
    public function testItCanIdentifySame(mixed $value, mixed $value2): void
    {
        $this->assertTrue(Assert::same($value, $value2));
    }

    #[DataProvider('invalidEqProvider')]
    public function testItWillThrowExceptionForSameWithSame(mixed $value, mixed $value2): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::same($value, $value2);
    }

    #[DataProvider('validResourceProvider')]
    public function testItCanIdentifyResource(mixed $value): void
    {
        $this->assertTrue(Assert::resource($value));
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForResourceWithResource(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::resource($value);
    }
}
