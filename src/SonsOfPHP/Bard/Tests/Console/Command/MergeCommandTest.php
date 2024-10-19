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
use SonsOfPHP\Bard\Worker\File\Composer\Package\Authors;
use SonsOfPHP\Bard\Worker\File\Composer\Package\BranchAlias;
use SonsOfPHP\Bard\Worker\File\Composer\Package\Funding;
use SonsOfPHP\Bard\Worker\File\Composer\Package\Support;
use SonsOfPHP\Bard\Worker\File\Composer\Root\ClearSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateAutoloadDevSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateAutoloadSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateProvideSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateReplaceSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateRequireDevSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateRequireSection;
use Symfony\Component\Console\Tester\CommandTester;

#[Group('bard')]
#[CoversClass(MergeCommand::class)]
#[UsesClass(Application::class)]
#[UsesClass(AbstractCommand::class)]
#[UsesClass(AddCommand::class)]
#[UsesClass(CopyCommand::class)]
#[UsesClass(InitCommand::class)]
#[UsesClass(InstallCommand::class)]
#[UsesClass(PullCommand::class)]
#[UsesClass(PushCommand::class)]
#[UsesClass(ReleaseCommand::class)]
#[UsesClass(SplitCommand::class)]
#[UsesClass(UpdateCommand::class)]
#[UsesClass(JsonFile::class)]
#[UsesClass(Authors::class)]
#[UsesClass(BranchAlias::class)]
#[UsesClass(Funding::class)]
#[UsesClass(Support::class)]
#[UsesClass(ClearSection::class)]
#[UsesClass(UpdateAutoloadDevSection::class)]
#[UsesClass(UpdateAutoloadSection::class)]
#[UsesClass(UpdateProvideSection::class)]
#[UsesClass(UpdateReplaceSection::class)]
#[UsesClass(UpdateRequireSection::class)]
#[UsesClass(UpdateRequireDevSection::class)]
final class MergeCommandTest extends TestCase
{
    private Application $application;

    private MergeCommand $command;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->command     = $this->application->get('merge');
    }

    public function testItExecutesSuccessfully(): void
    {
        $commandTester = new CommandTester($this->command);

        $commandTester->execute([
            '--dry-run' => true,
            '-vvv'      => true,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
