<?php

declare(strict_types=1);

namespace Chorale\Tests\Console;

use Chorale\Console\RunCommand;
use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Plan\PackageVersionUpdateStep;
use Chorale\Run\RunnerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(RunCommand::class)]
#[Group('unit')]
#[Small]
final class RunCommandTest extends TestCase
{
    #[Test]
    public function testRunExecutesRunner(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-run-cmd-' . uniqid();
        mkdir($projectRoot);

        $runner = $this->createMock(RunnerInterface::class);
        $runner->expects($this->once())->method('run')->with($projectRoot, $this->anything())->willReturn([
            'steps' => [new PackageVersionUpdateStep('pkg', 'pkg/pkg', '1.0.0')],
        ]);

        $command = new RunCommand(new ConsoleStyleFactory(), $runner);
        $tester = new CommandTester($command);
        $exitCode = $tester->execute(['--project-root' => $projectRoot]);
        $this->assertSame(0, $exitCode);
    }
}
