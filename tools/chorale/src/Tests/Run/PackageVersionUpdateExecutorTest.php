<?php

declare(strict_types=1);

namespace Chorale\Tests\Run;

use Chorale\Run\PackageVersionUpdateExecutor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PackageVersionUpdateExecutor::class)]
#[Group('unit')]
#[Small]
final class PackageVersionUpdateExecutorTest extends TestCase
{
    #[Test]
    public function testExecuteUpdatesComposerJson(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-exec-test-' . uniqid();
        mkdir($projectRoot . '/pkg', 0777, true);
        file_put_contents($projectRoot . '/pkg/composer.json', json_encode(['name' => 'pkg/pkg'], JSON_PRETTY_PRINT));

        $executor = new PackageVersionUpdateExecutor();
        $executor->execute($projectRoot, ['type' => 'package-version-update', 'path' => 'pkg', 'version' => '2.0.0']);

        $data = json_decode((string) file_get_contents($projectRoot . '/pkg/composer.json'), true);
        $this->assertSame('2.0.0', $data['version']);
    }
}
