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
use SonsOfPHP\Bard\Worker\File\Bard\UpdateVersionWorker;
use SonsOfPHP\Bard\Worker\File\Composer\Package\BranchAlias;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateReplaceSection;
use SonsOfPHP\Component\Version\Version;
use Symfony\Component\Console\Tester\CommandTester;

#[Group('bard')]
#[CoversClass(ReleaseCommand::class)]
#[UsesClass(Application::class)]
#[UsesClass(AbstractCommand::class)]
#[UsesClass(AddCommand::class)]
#[UsesClass(CopyCommand::class)]
#[UsesClass(InitCommand::class)]
#[UsesClass(InstallCommand::class)]
#[UsesClass(MergeCommand::class)]
#[UsesClass(PullCommand::class)]
#[UsesClass(PushCommand::class)]
#[UsesClass(SplitCommand::class)]
#[UsesClass(UpdateCommand::class)]
#[UsesClass(JsonFile::class)]
#[UsesClass(UpdateVersionWorker::class)]
#[UsesClass(BranchAlias::class)]
#[UsesClass(UpdateReplaceSection::class)]
#[UsesClass(Version::class)]
final class ReleaseCommandTest extends TestCase
{
    private Application $application;

    private ReleaseCommand $command;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->command     = $this->application->get('release');
    }

    public function testItsNameIsCorrect(): void
    {
        $commandTester = new CommandTester($this->command);

        $commandTester->execute([
            'release'   => 'patch',
            '--dry-run' => true,
            '-vvv'      => true,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
