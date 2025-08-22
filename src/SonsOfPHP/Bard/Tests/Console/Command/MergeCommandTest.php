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
use SonsOfPHP\Bard\Operation\ClearSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyAuthorsSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyBranchAliasValueFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyFundingSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopySupportSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateAutoloadDevSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateAutoloadSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateProvideSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateReplaceSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateRequireDevSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateRequireSectionOperation;
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
#[UsesClass(CopyAuthorsSectionFromRootToPackageOperation::class)]
#[UsesClass(CopyBranchAliasValueFromRootToPackageOperation::class)]
#[UsesClass(CopyFundingSectionFromRootToPackageOperation::class)]
#[UsesClass(CopySupportSectionFromRootToPackageOperation::class)]
#[UsesClass(ClearSectionOperation::class)]
#[UsesClass(UpdateAutoloadDevSectionOperation::class)]
#[UsesClass(UpdateAutoloadSectionOperation::class)]
#[UsesClass(UpdateProvideSectionOperation::class)]
#[UsesClass(UpdateReplaceSectionOperation::class)]
#[UsesClass(UpdateRequireSectionOperation::class)]
#[UsesClass(UpdateRequireDevSectionOperation::class)]
final class MergeCommandTest extends TestCase
{
    private MergeCommand $command;

    protected function setUp(): void
    {
        $application = new Application();
        $this->command     = $application->get('merge');
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
