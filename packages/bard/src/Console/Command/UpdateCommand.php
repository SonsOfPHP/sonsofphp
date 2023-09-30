<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateCommand extends AbstractCommand
{
    protected static $defaultName = 'update';

    // public function __construct()
    // {
    //    parent::__construct();
    // }

    protected function configure(): void
    {
        $this
            ->setDescription('Runs composer update for all packages')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardJsonFile = new JsonFile($input->getOption('working-dir') . '/bard.json');
        foreach ($bardJsonFile->getSection('packages') as $pkg) {
            $process = new Process([
                'composer',
                'update',
                '--with-all-dependencies',
                '--optimize-autoloader',
                '--no-progress',
                '--no-interaction',
                '--ansi',
                '--working-dir',
                $pkg['path'],
            ]);
            $this->getHelper('process')->run($output, $process);
        }

        return self::SUCCESS;
    }
}
