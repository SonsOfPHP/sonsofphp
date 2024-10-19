<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Console\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\Console\Application;
use SonsOfPHP\Bard\Console\Command\AbstractCommand;
use SonsOfPHP\Bard\Console\Command\AddCommand;
use SonsOfPHP\Bard\Console\Command\CopyCommand;
use SonsOfPHP\Bard\Console\Command\InitCommand;
use SonsOfPHP\Bard\Console\Command\InstallCommand;
use SonsOfPHP\Bard\Console\Command\MergeCommand;
use SonsOfPHP\Bard\Console\Command\PullCommand;
use SonsOfPHP\Bard\Console\Command\PushCommand;
use SonsOfPHP\Bard\Console\Command\ReleaseCommand;
use SonsOfPHP\Bard\Console\Command\SplitCommand;
use SonsOfPHP\Bard\Console\Command\UpdateCommand;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\File\Bard\AddPackageWorker;
use Symfony\Component\Console\Tester\CommandTester;

#[Group('bard')]
#[CoversClass(AddCommand::class)]
#[UsesClass(Application::class)]
#[UsesClass(AbstractCommand::class)]
#[UsesClass(CopyCommand::class)]
#[UsesClass(InitCommand::class)]
#[UsesClass(InstallCommand::class)]
#[UsesClass(MergeCommand::class)]
#[UsesClass(PullCommand::class)]
#[UsesClass(PushCommand::class)]
#[UsesClass(ReleaseCommand::class)]
#[UsesClass(SplitCommand::class)]
#[UsesClass(UpdateCommand::class)]
#[UsesClass(JsonFile::class)]
#[UsesClass(AddPackageWorker::class)]
final class AddCommandTest extends TestCase
{
    private Application $application;

    private AddCommand $command;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->command     = $this->application->get('add');
    }

    public function testItsNameIsCorrect(): void
    {
        $commandTester = new CommandTester($this->command);

        $commandTester->execute([
            'path'       => 'tmp/repo',
            'repository' => 'git@repo:repo.git',
            '--dry-run'  => true,
            '-vvv'  => true,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
