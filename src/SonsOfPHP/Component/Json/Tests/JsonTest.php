<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Json\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Json\Json;
use SonsOfPHP\Component\Json\JsonException;

/**
 *
 * @uses \SonsOfPHP\Component\Json\AbstractEncoderDecoder
 * @uses \SonsOfPHP\Component\Json\JsonDecoder
 * @uses \SonsOfPHP\Component\Json\JsonEncoder
 * @coversNothing
 */
#[CoversClass(Json::class)]
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
    public function testEncode(): void
    {
        $value  = ['test' => true];
        $return = Json::encode($value);
        $this->assertSame('{"test":true}', $return);
    }

    public function testEncodeOnFuckedUpJson(): void
    {
        $value = "\xB1\x31"; // invalid UTF8
        $this->expectException(JsonException::class);
        Json::encode($value);
    }

    public function testDecode(): void
    {
        $json   = '{"test":true}';
        $return = Json::decode($json);
        $this->assertInstanceOf('stdClass', $return);
        $this->assertTrue($return->test);
    }

    public function testDecodeOnFuckedUpJson(): void
    {
        $json = '{"test:true}';
        $this->expectException(JsonException::class);
        Json::decode($json);
    }

    public function testDecodeWillReturnAnArray(): void
    {
        $json   = '{"test":true}';
        $return = Json::decode($json, true);
        $this->assertIsArray($return);
        $this->assertTrue($return['test']);
    }
}
