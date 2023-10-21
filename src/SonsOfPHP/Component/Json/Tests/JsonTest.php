<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Json\Json;
use SonsOfPHP\Component\Json\JsonException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Json\Json
 *
 * @internal
 */
final class JsonTest extends TestCase
{
    // @todo make JsonEncoderInterface
    // public function testGetEncoderReturnsTheCorrectInstanceOf(): void
    // {
    //    $json = new Json();
    //    $this->assertInstanceOf(JsonEncoderInterface::class, $json->getEncoder());
    // }

    // @todo make JsonEncoderInterface
    // public function testGetDecoderReturnsTheCorrectInstanceOf(): void
    // {
    //    $json = new Json();
    //    $this->assertInstanceOf(JsonDecoderInterface::class, $json->getDecoder());
    // }

    /**
     * @covers ::encode
     */
    public function testEncode(): void
    {
        $value  = ['test' => true];
        $return = Json::encode($value);
        $this->assertSame('{"test":true}', $return);
    }

    /**
     * @covers ::encode
     */
    public function testEncodeOnFuckedUpJson(): void
    {
        $value = "\xB1\x31"; // invalid UTF8
        $this->expectException(JsonException::class);
        Json::encode($value);
    }

    /**
     * @covers ::decode
     */
    public function testDecode(): void
    {
        $json   = '{"test":true}';
        $return = Json::decode($json);
        $this->assertInstanceOf('stdClass', $return);
        $this->assertTrue($return->test);
    }

    /**
     * @covers ::decode
     */
    public function testDecodeOnFuckedUpJson(): void
    {
        $json = '{"test:true}';
        $this->expectException(JsonException::class);
        Json::decode($json);
    }

    /**
     * @covers ::decode
     */
    public function testDecodeWillReturnAnArray(): void
    {
        $json   = '{"test":true}';
        $return = Json::decode($json, true);
        $this->assertIsArray($return);
        $this->assertTrue($return['test']);
    }
}
