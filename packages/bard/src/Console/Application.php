<?php

namespace SonsOfPHP\Bard\Console;

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
            new \SonsOfPHP\Bard\Console\Command\InitCommand(),
            new \SonsOfPHP\Bard\Console\Command\InstallCommand(),
            new \SonsOfPHP\Bard\Console\Command\MergeCommand(),
            new \SonsOfPHP\Bard\Console\Command\ReleaseCommand(),
            new \SonsOfPHP\Bard\Console\Command\RunCommand(),
            new \SonsOfPHP\Bard\Console\Command\UpdateCommand(),
        ]);
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('working-dir', 'd', InputOption::VALUE_REQUIRED, 'Working Directory', getcwd()));
        //$definition->addOption(new InputOption('branch', 'b', InputOption::VALUE_REQUIRED, 'Mainline Branch', 'main'));

        return $definition;
    }
}
