<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Console;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\Console\Application;
use SonsOfPHP\Bard\Console\Command\AddCommand;
use SonsOfPHP\Bard\Console\Command\CopyCommand;
use SonsOfPHP\Bard\Console\Command\InitCommand;
use SonsOfPHP\Bard\Console\Command\InstallCommand;
use SonsOfPHP\Bard\Console\Command\MergeCommand;
use SonsOfPHP\Bard\Console\Command\PullCommand;
use SonsOfPHP\Bard\Console\Command\PushCommand;
use SonsOfPHP\Bard\Console\Command\ReleaseCommand;
use SonsOfPHP\Bard\Console\Command\SplitCommand;

;
use SonsOfPHP\Bard\Console\Command\UpdateCommand;

#[Group('bard')]
#[CoversClass(Application::class)]
#[UsesClass(AddCommand::class)]
#[UsesClass(CopyCommand::class)]
#[UsesClass(InitCommand::class)]
#[UsesClass(InstallCommand::class)]
#[UsesClass(MergeCommand::class)]
#[UsesClass(PullCommand::class)]
#[UsesClass(PushCommand::class)]
#[UsesClass(ReleaseCommand::class)]
#[UsesClass(SplitCommand::class)]
#[UsesClass(UpdateCommand::class)]
final class ApplicationTest extends TestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();
    }

    public function testItsNameIsCorrect(): void
    {
        $this->assertSame('Bard', $this->application->getName());
    }

    public function testItHasAddCommand(): void
    {
        $this->assertTrue($this->application->has('add'));
    }

    public function testItHasCopyCommand(): void
    {
        $this->assertTrue($this->application->has('copy'));
    }

    public function testItHasInitCommand(): void
    {
        $this->assertTrue($this->application->has('init'));
    }

    public function testItHasInstallCommand(): void
    {
        $this->assertTrue($this->application->has('install'));
    }

    public function testItHasMergeCommand(): void
    {
        $this->assertTrue($this->application->has('merge'));
    }
}
