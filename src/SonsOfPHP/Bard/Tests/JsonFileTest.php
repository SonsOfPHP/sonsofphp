<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Component\Json\AbstractEncoderDecoder;
use SonsOfPHP\Component\Json\Json;
use SonsOfPHP\Component\Json\JsonDecoder;

#[CoversClass(JsonFile::class)]
#[UsesClass(AbstractEncoderDecoder::class)]
#[UsesClass(Json::class)]
#[UsesClass(JsonDecoder::class)]
final class JsonFileTest extends TestCase
{
    public function testGetFilename(): void
    {
        $json = new JsonFile(__DIR__ . '/fixtures/test.json');

        $this->assertIsString($json->getFilename());
    }

    public function testGetSection(): void
    {
        $json = new JsonFile(__DIR__ . '/fixtures/test.json');

        $this->assertSame('1.2.3', $json->getSection('version'));
    }
}
