<?php

declare(strict_types=1);

namespace Chorale\Tests\Run;

use Chorale\Run\ComposerRootUpdateExecutor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ComposerRootUpdateExecutor::class)]
#[Group('unit')]
#[Small]
final class ComposerRootUpdateExecutorTest extends TestCase
{
    #[Test]
    public function testExecuteUpdatesRootComposer(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-update-' . uniqid();
        mkdir($projectRoot);
        file_put_contents($projectRoot . '/composer.json', json_encode(['name' => 'old/root'], JSON_PRETTY_PRINT));

        $executor = new ComposerRootUpdateExecutor();
        $executor->execute($projectRoot, [
            'type' => 'composer-root-update',
            'root' => 'acme/monorepo',
            'root_version' => '1.0.0',
            'require' => ['foo/bar' => '*'],
            'replace' => ['foo/bar' => '*'],
        ]);

        $data = json_decode((string) file_get_contents($projectRoot . '/composer.json'), true);
        $this->assertSame('acme/monorepo', $data['name']);
        $this->assertSame('1.0.0', $data['version']);
        $this->assertSame(['foo/bar' => '*'], $data['require']);
        $this->assertSame(['foo/bar' => '*'], $data['replace']);
    }
}
