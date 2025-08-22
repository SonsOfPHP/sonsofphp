<?php

declare(strict_types=1);

namespace Chorale\Tests\Run;

use Chorale\Run\RootDependencyMergeExecutor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RootDependencyMergeExecutor::class)]
#[Group('unit')]
#[Small]
final class RootDependencyMergeExecutorTest extends TestCase
{
    #[Test]
    public function testExecuteMergesDependenciesIntoRootComposer(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-merge-' . uniqid();
        mkdir($projectRoot);
        file_put_contents($projectRoot . '/composer.json', json_encode(['name' => 'acme/root'], JSON_PRETTY_PRINT));

        $executor = new RootDependencyMergeExecutor();
        $executor->execute($projectRoot, [
            'type' => 'composer-root-merge',
            'require' => ['foo/bar' => '^1.0'],
            'require-dev' => ['baz/qux' => '^2.0'],
        ]);

        $data = json_decode((string) file_get_contents($projectRoot . '/composer.json'), true);
        $this->assertSame(['foo/bar' => '^1.0'], $data['require']);
        $this->assertSame(['baz/qux' => '^2.0'], $data['require-dev']);
        $this->assertArrayNotHasKey('extra', $data);
    }

    #[Test]
    public function testExecuteRecordsConflicts(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-merge-conflict-' . uniqid();
        mkdir($projectRoot);
        file_put_contents($projectRoot . '/composer.json', json_encode(['name' => 'acme/root'], JSON_PRETTY_PRINT));

        $conflict = [['package' => 'foo/bar', 'versions' => ['^1.0', '1.2.0'], 'packages' => ['a', 'b'], 'reason' => 'non-caret-mixed']];

        $executor = new RootDependencyMergeExecutor();
        $executor->execute($projectRoot, [
            'type' => 'composer-root-merge',
            'require' => [],
            'require-dev' => [],
            'conflicts' => $conflict,
        ]);

        $data = json_decode((string) file_get_contents($projectRoot . '/composer.json'), true);
        $this->assertSame($conflict, $data['extra']['chorale']['dependency-conflicts']);
    }
}
