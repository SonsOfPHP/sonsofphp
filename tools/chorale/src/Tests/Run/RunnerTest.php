<?php

declare(strict_types=1);

namespace Chorale\Tests\Run;

use Chorale\Config\ConfigLoaderInterface;
use Chorale\Plan\PackageVersionUpdateStep;
use Chorale\Plan\PlanBuilderInterface;
use Chorale\Run\PackageVersionUpdateExecutor;
use Chorale\Run\Runner;
use Chorale\Run\StepExecutorRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Runner::class)]
#[Group('unit')]
#[Small]
final class RunnerTest extends TestCase
{
    #[Test]
    public function testRunAppliesPackageVersionUpdate(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-runner-test-' . uniqid();
        mkdir($projectRoot . '/pkg', 0o777, true);
        file_put_contents($projectRoot . '/pkg/composer.json', json_encode(['name' => 'pkg/pkg'], JSON_PRETTY_PRINT));

        $configLoader = $this->createStub(ConfigLoaderInterface::class);
        $configLoader->method('load')->willReturn(['packages' => []]);

        $step = new PackageVersionUpdateStep('pkg', 'pkg/pkg', '1.2.3');
        $planner = $this->createMock(PlanBuilderInterface::class);
        $planner->method('build')->willReturn(['steps' => [$step]]);

        $runner = new Runner($configLoader, $planner, new StepExecutorRegistry([new PackageVersionUpdateExecutor()]));
        $runner->run($projectRoot);

        $data = json_decode((string) file_get_contents($projectRoot . '/pkg/composer.json'), true);
        $this->assertSame('1.2.3', $data['version']);
    }
}
