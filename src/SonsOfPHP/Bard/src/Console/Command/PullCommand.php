<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class PullCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('pull')
            ->setDescription('Pull changes from package repos')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardConfig = new JsonFile($input->getOption('working-dir') . '/bard.json');
        $formatter  = $this->getHelper('formatter');
        $io         = new SymfonyStyle($input, $output);

        foreach ($bardConfig->getSection('packages') as $pkg) {
            $pkgComposerFile     = realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json');
            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName             = $pkgComposerJsonFile->getSection('name');
            $io->text(sprintf('Pushing <info>%s</>', $pkgName));

            $commands = [
                ['git', 'subtree', 'pull', '-P', $pkg['path'], $pkg['repository'], $input->getOption('branch'), '--squash'],
            ];

            foreach ($commands as $cmd) {
                $process = new Process($cmd);
                $io->text($process->getCommandLine());
                $this->getHelper('process')
                    ->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()))
                    ->wait();
            }
        }

        $io->success('All Packages have been published.');

        return self::SUCCESS;
    }
}
