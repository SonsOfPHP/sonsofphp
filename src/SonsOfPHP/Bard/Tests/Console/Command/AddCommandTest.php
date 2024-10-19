<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Console\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\Console\Application;
use SonsOfPHP\Bard\Console\Command\AddCommand;
use Symfony\Component\Console\Tester\CommandTester;

#[Group('bard')]
#[CoversClass(AddCommand::class)]
final class AddCommandTest extends TestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();
    }

    public function testItsNameIsCorrect(): void
    {
        $command = $this->application->get('add');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'path'       => 'tmp/repo',
            'repository' => 'git@repo:repo.git',
            '--dry-run'  => true,
            '-vvv'  => true,
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
