<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert\Tests;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Assert\Assert;
use SonsOfPHP\Component\Assert\InvalidArgumentException;

#[CoversClass(Assert::class)]
final class AssertTest extends TestCase
{
    public static function validStringProvider(): Generator
    {
        yield ['test'];
    }

    public static function invalidStringProvider(): Generator
    {
        yield [0];
    }

    #[DataProvider('validStringProvider')]
    public function testItCanIdentifyAString(mixed $value): void
    {
        $this->assertTrue(Assert::string($value));
    }

    #[DataProvider('invalidStringProvider')]
    public function testItCanIdentifyANotString(mixed $value): void
    {
        $this->assertTrue(Assert::notString($value));
    }

    #[DataProvider('invalidStringProvider')]
    public function testItWillThrowExceptionForStringWithInvalidString(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(Assert::INVALID_STRING);
        Assert::string($value);
    }

    #[DataProvider('validStringProvider')]
    public function testItWillThrowExceptionForNotStringWithValidString(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::notString($value);
    }
}
