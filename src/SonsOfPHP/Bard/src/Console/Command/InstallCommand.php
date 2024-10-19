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
final class InstallCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('install')
            ->setDescription('Runs composer install for all packages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardJsonFile = new JsonFile($input->getOption('working-dir') . '/bard.json');
        foreach ($bardJsonFile->getSection('packages') as $pkg) {
            $process = new Process([
                'composer',
                'install',
                '--optimize-autoloader',
                '--no-progress',
                '--no-interaction',
                '--ansi',
                '--working-dir',
                $pkg['path'],
            ]);
            $this->getProcessHelper()->run($output, $process);
        }

        return self::SUCCESS;
    }
}
