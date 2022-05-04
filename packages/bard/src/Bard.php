<?php

namespace SonsOfPHP\Bard;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Bard extends Application
{
    public const VERSION = '0.1.x';

    public function __construct()
    {
        parent::__construct('Bard', self::VERSION);
    }

    protected function getDefaultCommands(): array
    {
        return array_merge(parent::getDefaultCommands(), [
            new \SonsOfPHP\Bard\Command\ConfigCommand(),
            new \SonsOfPHP\Bard\Command\InitCommand(),
            new \SonsOfPHP\Bard\Command\InstallCommand(),
            new \SonsOfPHP\Bard\Command\MergeCommand(),
            new \SonsOfPHP\Bard\Command\ReleaseCommand(),
            new \SonsOfPHP\Bard\Command\ReleaseMajorCommand(),
            new \SonsOfPHP\Bard\Command\ReleaseMinorCommand(),
            new \SonsOfPHP\Bard\Command\ReleasePatchCommand(),
            new \SonsOfPHP\Bard\Command\RunCommand(),
            new \SonsOfPHP\Bard\Command\SplitCommand(),
            new \SonsOfPHP\Bard\Command\UpdateCommand(),
            new \SonsOfPHP\Bard\Command\ValidateCommand(),
        ]);
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('working-dir', 'd', InputOption::VALUE_REQUIRED, 'Working Directory', getcwd()));
        $definition->addOption(new InputOption('branch', 'b', InputOption::VALUE_REQUIRED, 'Mainline Branch', 'main'));

        return $definition;
    }
}
