<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console;

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
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Application extends BaseApplication
{
    public const VERSION = '0.1.x';

    public function __construct()
    {
        parent::__construct('Bard', self::VERSION);
    }

    protected function getDefaultCommands(): array
    {
        return array_merge(parent::getDefaultCommands(), [
            new AddCommand(),
            new CopyCommand(),
            new InitCommand(),
            new InstallCommand(),
            new MergeCommand(),
            new PullCommand(),
            new PushCommand(),
            new ReleaseCommand(),
            new SplitCommand(),
            new UpdateCommand(),
        ]);
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('working-dir', 'd', InputOption::VALUE_REQUIRED, 'Working Directory', getcwd()));
        // $definition->addOption(new InputOption('branch', 'b', InputOption::VALUE_REQUIRED, 'Mainline Branch', 'main'));

        return $definition;
    }
}
