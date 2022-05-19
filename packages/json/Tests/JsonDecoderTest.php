<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SonsOfPHP\Component\Json\JsonDecoder;
use SonsOfPHP\Component\Json\JsonException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Json\JsonDecoder
 */
final class JsonDecoderTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::__construct
     */
    public function testConstructCanReturnArray(): void
    {
        $decoder = new JsonDecoder(true);
        $ref = new ReflectionObject($decoder);
        $prop = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $this->assertSame(JSON_OBJECT_AS_ARRAY, $prop->getValue($decoder));
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     */
    public function testWithFlagsReturnsNewObject(): void
    {
        $decoder = new JsonDecoder();
        $decoderOther = $decoder->withFlags(JSON_OBJECT_AS_ARRAY);

        $this->assertNotSame($decoder, $decoderOther);
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withoutFlags
     */
    public function testWithoutFlagsReturnsNewObject(): void
    {
        $decoder = new JsonDecoder();
        $decoderOther = $decoder->withoutFlags(JSON_OBJECT_AS_ARRAY);

        $this->assertNotSame($decoder, $decoderOther);
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withoutFlags
     */
    public function testWithoutFlagsDoesntRemoveMoreThanItShould(): void
    {
        $decoder = new JsonDecoder();
        $ref = new ReflectionObject($decoder);
        $prop = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $decoder = $decoder->withoutFlags(JSON_OBJECT_AS_ARRAY);
        $this->assertSame(0, $prop->getValue($decoder));

        $decoder = $decoder->withFlags(JSON_BIGINT_AS_STRING)->withoutFlags(JSON_OBJECT_AS_ARRAY);
        $this->assertSame(JSON_BIGINT_AS_STRING, $prop->getValue($decoder));

        $decoder = $decoder->withoutFlags(JSON_BIGINT_AS_STRING);
        $this->assertSame(0, $prop->getValue($decoder));
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withFlags
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withoutFlags
     */
    public function testWithoutFlagsRemovesFlag(): void
    {
        $decoder = new JsonDecoder();
        $ref = new ReflectionObject($decoder);
        $prop = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $decoder = $decoder->withFlags(JSON_OBJECT_AS_ARRAY);
        $this->assertSame(JSON_OBJECT_AS_ARRAY, $prop->getValue($decoder));

        $decoder = $decoder->withoutFlags(JSON_OBJECT_AS_ARRAY);
        $this->assertSame(0, $prop->getValue($decoder));
    }

    /**
     * @covers ::asArray
     */
    public function testAsArrayAddsCorrectFlag(): void
    {
        $decoder = new JsonDecoder();
        $ref = new ReflectionObject($decoder);
        $prop = $ref->getProperty('flags');
        $prop->setAccessible(true);

        $decoder = $decoder->asArray();
        $this->assertSame(JSON_OBJECT_AS_ARRAY, $prop->getValue($decoder));
    }

    /**
     * @covers \SonsOfPHP\Component\Json\AbstractEncoderDecoder::withDepth
     */
    public function testChangingDepthActuallyChangesDepth(): void
    {
        $decoder = new JsonDecoder();
        $ref = new ReflectionObject($decoder);
        $prop = $ref->getProperty('depth');
        $prop->setAccessible(true);

        $decoder = $decoder->withDepth(123);
        $this->assertSame(123, $prop->getValue($decoder));
    }

    /**
     * @covers ::decode
     */
    public function testDecodeOnSimpleJsonString(): void
    {
        $json = '{"test":true}';
        $decoder = new JsonDecoder();

        $return = $decoder->decode($json);
        $this->assertInstanceOf('stdClass', $return);
        $this->assertTrue($return->test);
    }

    /**
     * @covers ::decode
     */
    public function testDecodeOnFuckedUpJson(): void
    {
        $json = '{"test:true}';
        $decoder = new JsonDecoder();

        $this->expectException(JsonException::class);
        $decoder->decode($json);
    }
}
