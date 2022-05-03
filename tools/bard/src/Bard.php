<?php

namespace SonsOfPHP\Bard;

use Symfony\Component\Console\Application;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Bard extends Application
{
    public const VERSION = '0.1.x';

    public function __construct()
    {
        parent::__construct('Bard', self::VERSION);

        $this->addCommands([
            new \SonsOfPHP\Bard\Command\InitCommand(),
            new \SonsOfPHP\Bard\Command\MergeCommand(),
        ]);
    }
}
