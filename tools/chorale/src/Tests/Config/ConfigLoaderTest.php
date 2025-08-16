<?php

declare(strict_types=1);

namespace Chorale\Tests\Config;

use Chorale\Config\ConfigLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigLoader::class)]
#[Group('unit')]
#[Small]
final class ConfigLoaderTest extends TestCase
{
    public function testLoadReturnsEmptyArrayWhenMissingFile(): void
    {
        $loader = new ConfigLoader('test.yaml');
        $dir = sys_get_temp_dir() . '/chorale_' . uniqid();
        @mkdir($dir);
        $out = $loader->load($dir);
        $this->assertSame([], $out);
    }

    #[Test]
    public function testLoadParsesYamlIntoArray(): void
    {
        $loader = new ConfigLoader('test.yaml');
        $dir = sys_get_temp_dir() . '/chorale_' . uniqid();
        @mkdir($dir);
        file_put_contents($dir . '/test.yaml', "repo_vendor: Acme\n");
        $out = $loader->load($dir);
        $this->assertSame('Acme', $out['repo_vendor']);
    }
}
