<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFile;

/**
 * @coversDefaultClass \SonsOfPHP\Bard\JsonFile
 *
 * @uses \SonsOfPHP\Bard\JsonFile
 * @uses \SonsOfPHP\Component\Json\AbstractEncoderDecoder
 * @uses \SonsOfPHP\Component\Json\Json
 * @uses \SonsOfPHP\Component\Json\JsonDecoder
 */
final class JsonFileTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::load
     * @covers ::getFilename
     * @covers ::getSection
     */
    public function testGetFilename(): void
    {
        $json = new JsonFile(__DIR__ . '/fixtures/test.json');

        $this->assertIsString($json->getFilename());
    }

    /**
     * @covers ::__construct
     * @covers ::load
     * @covers ::getSection
     */
    public function testGetSection(): void
    {
        $json = new JsonFile(__DIR__ . '/fixtures/test.json');

        $this->assertSame('1.2.3', $json->getSection('version'));
    }
}
