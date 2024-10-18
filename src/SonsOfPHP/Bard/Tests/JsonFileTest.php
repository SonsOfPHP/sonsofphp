<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFile;

#[Group('bard')]
#[CoversClass(JsonFile::class)]
final class JsonFileTest extends TestCase
{
    private function getDefaultConfig(): array
    {
        return [
            'version' => '1.2.3',
            'packages' => [
                [
                    'path'       => 'path/to/Repo',
                    'repository' => 'git@github.com:SonsOfPHP/read-only-repo.git',
                ],
            ],
        ];
    }

    //protected function setUp(): void
    //{
    //}

    protected function tearDown(): void
    {
        $file = new JsonFile(__DIR__ . '/fixtures/test.json');
        $file->setConfig($this->getDefaultConfig());
        $file->save();
    }

    public function testItLoadsDefaultConfig(): void
    {
        $file = new JsonFile(__DIR__ . '/fixtures/test.json');
        $this->assertSame($this->getDefaultConfig(), $file->getConfig());
    }

    public function testItsAbleToReturnCorrectSection(): void
    {
        $file = new JsonFile(__DIR__ . '/fixtures/test.json');

        $this->assertSame('1.2.3', $file->getSection('version'));
    }

    public function testItCanUpdateExistingSection(): void
    {
        $file = new JsonFile(__DIR__ . '/fixtures/test.json');
        $file = $file->setSection('version', '1.2.4');
        $this->assertSame('1.2.4', $file->getSection('version'));
    }

    public function testItCanConvertUpdatedConfigToJson(): void
    {
        $file = new JsonFile(__DIR__ . '/fixtures/test.json');
        $file = $file->setSection('version', '1.2.4');

        $json = json_decode($file->toJson(), true);

        $this->assertArrayHasKey('version', $json);
        $this->assertSame('1.2.4', $json['version']);
    }
}
