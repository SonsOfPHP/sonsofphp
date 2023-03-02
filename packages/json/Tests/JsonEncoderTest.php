<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SonsOfPHP\Component\Json\JsonEncoder;
use SonsOfPHP\Component\Json\JsonException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Json\JsonEncoder
 *
 * @internal
 */
final class JsonEncoderTest extends TestCase
{
    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::__construct
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     */
    public function testWithFlagsReturnsNewObject(): void
    {
        $encoder      = new JsonEncoder();
        $encoderOther = $encoder->withFlags(\JSON_PRETTY_PRINT);

        $this->assertNotSame($encoder, $encoderOther);
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::__construct
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withOutFlags
     */
    public function testWithoutFlagsReturnsNewObject(): void
    {
        $encoder      = new JsonEncoder();
        $encoderOther = $encoder->withoutFlags(\JSON_PRETTY_PRINT);

        $this->assertNotSame($encoder, $encoderOther);
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::__construct
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withOutFlags
     */
    public function testWithoutFlagsDoesntRemoveMoreThanItShould(): void
    {
        $encoder = new JsonEncoder();
        $ref     = new ReflectionObject($encoder);
        $prop    = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $encoder = $encoder->withoutFlags(\JSON_PRETTY_PRINT);
        $this->assertSame(0, $prop->getValue($encoder));

        $encoder = $encoder->withFlags(\JSON_UNESCAPED_UNICODE)->withoutFlags(\JSON_PRETTY_PRINT);
        $this->assertSame(\JSON_UNESCAPED_UNICODE, $prop->getValue($encoder));

        $encoder = $encoder->withoutFlags(\JSON_UNESCAPED_UNICODE);
        $this->assertSame(0, $prop->getValue($encoder));
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::__construct
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withOutFlags
     */
    public function testWithoutFlagsRemovesFlag(): void
    {
        $encoder = new JsonEncoder();
        $ref     = new ReflectionObject($encoder);
        $prop    = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $encoder = $encoder->withFlags(\JSON_PRETTY_PRINT);
        $this->assertSame(\JSON_PRETTY_PRINT, $prop->getValue($encoder));

        $encoder = $encoder->withoutFlags(\JSON_PRETTY_PRINT);
        $this->assertSame(0, $prop->getValue($encoder));
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withDepth
     */
    public function testChangingDepthActuallyChangesDepth(): void
    {
        $encoder = new JsonEncoder();
        $ref     = new ReflectionObject($encoder);
        $prop    = $ref->getProperty('depth');
        $prop->setAccessible(true);

        $encoder = $encoder->withDepth(123);
        $this->assertSame(123, $prop->getValue($encoder));
    }

    /**
     * @covers ::encode
     */
    public function testEncodeOnSimpleJsonString(): void
    {
        $value   = ['test' => true];
        $encoder = new JsonEncoder();

        $return = $encoder->encode($value);
        $this->assertSame('{"test":true}', $return);
    }

    /**
     * @covers ::encode
     */
    public function testEncodeOnFuckedUpJson(): void
    {
        $value   = "\xB1\x31"; // invalid UTF8
        $encoder = new JsonEncoder();

        $this->expectException(JsonException::class);
        $encoder->encode($value);
    }
}
