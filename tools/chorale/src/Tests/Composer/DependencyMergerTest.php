<?php

declare(strict_types=1);

namespace Chorale\Tests\Composer;

use Chorale\Composer\ComposerJsonReaderInterface;
use Chorale\Composer\DependencyMerger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DependencyMerger::class)]
#[Group('unit')]
#[Small]
final class DependencyMergerTest extends TestCase
{
    #[Test]
    public function testComputeRootMergeMergesPackageRequirementsUsingUnionCaretStrategy(): void
    {
        $reader = new class implements ComposerJsonReaderInterface {
            public function read(string $absolutePath): array
            {
                if (str_contains($absolutePath, 'pkg1')) {
                    return ['name' => 'pkg1', 'require' => ['foo/bar' => '^1.0']];
                }

                if (str_contains($absolutePath, 'pkg2')) {
                    return ['name' => 'pkg2', 'require' => ['foo/bar' => '^1.2']];
                }

                return [];
            }
        };

        $merger = new DependencyMerger($reader);
        $result = $merger->computeRootMerge('/root', ['pkg1', 'pkg2']);

        $this->assertSame(['foo/bar' => '^1.2'], $result['require']);
        $this->assertSame([], $result['conflicts']);
    }

    #[Test]
    public function testComputeRootMergeRecordsConflictWhenMixedConstraintTypes(): void
    {
        $reader = new class implements ComposerJsonReaderInterface {
            public function read(string $absolutePath): array
            {
                if (str_contains($absolutePath, 'pkg1')) {
                    return ['name' => 'pkg1', 'require' => ['foo/bar' => '^1.0']];
                }

                if (str_contains($absolutePath, 'pkg2')) {
                    return ['name' => 'pkg2', 'require' => ['foo/bar' => '1.3.0']];
                }

                return [];
            }
        };

        $merger = new DependencyMerger($reader);
        $result = $merger->computeRootMerge('/root', ['pkg1', 'pkg2']);

        $this->assertSame(['foo/bar' => '^1.0'], $result['require']);
        $this->assertSame('non-caret-mixed', $result['conflicts'][0]['reason']);
    }
}
