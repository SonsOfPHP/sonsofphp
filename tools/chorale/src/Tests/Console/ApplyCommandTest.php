<?php

declare(strict_types=1);

namespace Chorale\Tests\Console;

use Chorale\Console\ApplyCommand;
use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Run\RunnerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(ApplyCommand::class)]
#[Group('unit')]
#[Small]
final class ApplyCommandTest extends TestCase
{
    #[Test]
    public function testApplyRunsStepsFromFile(): void
    {
        $projectRoot = sys_get_temp_dir() . '/chorale-apply-test-' . uniqid();
        mkdir($projectRoot);
        $planPath = $projectRoot . '/plan.json';
        file_put_contents($planPath, json_encode(['steps' => [['type' => 'x']]]));

        $runner = $this->createMock(RunnerInterface::class);
        $runner->expects($this->once())->method('apply')->with($projectRoot, [['type' => 'x']]);

        $command = new ApplyCommand(new ConsoleStyleFactory(), $runner);
        $tester = new CommandTester($command);
        $exitCode = $tester->execute(['--project-root' => $projectRoot, '--file' => $planPath]);
        $this->assertSame(0, $exitCode);
    }
}
