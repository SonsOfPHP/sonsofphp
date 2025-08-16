<?php

declare(strict_types=1);

namespace Chorale\Tests\Discovery;

use Chorale\Discovery\PackageScanner;
use Chorale\Util\PathUtils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PackageScanner::class)]
#[Group('unit')]
#[Small]
final class PackageScannerTest extends TestCase
{
    private function makeProject(): string
    {
        $root = sys_get_temp_dir() . '/chorale_proj_' . uniqid();
        @mkdir($root);
        @mkdir($root . '/src');
        // package candidate: depth >= 2 and has a file
        @mkdir($root . '/src/SonsOfPHP/Cookie', 0o777, true);
        file_put_contents($root . '/src/SonsOfPHP/Cookie/composer.json', '{}');
        // non-candidate: only dirs, no file
        @mkdir($root . '/src/Empty/NoFiles', 0o777, true);
        return $root;
    }

    #[Test]
    public function testScanFindsLeafPackages(): void
    {
        $root = $this->makeProject();
        $ps = new PackageScanner(new PathUtils());
        $paths = $ps->scan($root, 'src');
        $this->assertContains('src/SonsOfPHP/Cookie', $paths);
    }

    #[Test]
    public function testScanRespectsProvidedPaths(): void
    {
        $root = $this->makeProject();
        $ps = new PackageScanner(new PathUtils());
        $paths = $ps->scan($root, 'src', ['src/SonsOfPHP/Cookie']);
        $this->assertSame(['src/SonsOfPHP/Cookie'], $paths);
    }
}
