<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFile;

/**
 *
 * @uses \SonsOfPHP\Bard\JsonFile
 * @uses \SonsOfPHP\Component\Json\AbstractEncoderDecoder
 * @uses \SonsOfPHP\Component\Json\Json
 * @uses \SonsOfPHP\Component\Json\JsonDecoder
 * @coversNothing
 */
#[CoversClass(JsonFile::class)]
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
