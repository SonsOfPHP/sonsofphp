<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputArgument;
use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CopyCommand extends AbstractCommand
{
    protected static $defaultName = 'copy';

    protected function configure(): void
    {
        $this
            ->setDescription('Copies a file to each package')
            ->addArgument('source', InputArgument::REQUIRED, 'Source file to copy')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io         = new SymfonyStyle($input, $output);
        $sourceFile = $input->getOption('working-dir') . '/' . $input->getArgument('source');
        if (!is_file($sourceFile)) {
            throw new \RuntimeException(sprintf('The file "%s" is an invalid file.', $sourceFile));
        }
        $bardJsonFile = new JsonFile($input->getOption('working-dir') . '/bard.json');
        foreach ($bardJsonFile->getSection('packages') as $pkg) {
            $process = new Process(['cp', $sourceFile, $pkg['path']]);
            $io->text($process->getCommandLine());
            $this->getHelper('process')->run($output, $process);
        }

        $io->success('File has been copied.');

        return self::SUCCESS;
    }
}
