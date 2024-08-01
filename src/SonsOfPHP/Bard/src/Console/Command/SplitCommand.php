<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class SplitCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('split')
            ->setDescription('Push changes to package repos using git subtree split')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addArgument('package', InputArgument::OPTIONAL, 'Which package do you want to push?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardConfig = new JsonFile($input->getOption('working-dir') . '/bard.json');
        $io         = new SymfonyStyle($input, $output);
        $isDryRun   = $input->getOption('dry-run');

        foreach ($bardConfig->getSection('packages') as $pkg) {
            $pkgComposerFile     = realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json');
            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName             = $pkgComposerJsonFile->getSection('name');

            if (null !== $input->getArgument('package') && $pkgName !== $input->getArgument('package')) {
                continue;
            }

            $commands = [
                // subtree split
                ['git', 'subtree', 'split', '-P', $pkg['path'], '-b', $pkgName],
                ['git', 'checkout', $pkgName],
                ['git', 'push', $pkg['repository'], sprintf('%s:%s', $pkgName, $input->getOption('branch'))],
                ['git', 'checkout', $input->getOption('branch')],
                ['git', 'branch', '-D', $pkgName],
            ];

            $io->text(sprintf('Pushing <info>%s</>', $pkgName));
            foreach ($commands as $cmd) {
                $process = new Process($cmd);
                $io->text($process->getCommandLine());
                if (!$isDryRun) {
                    $this->getHelper('process')
                        ->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()))
                        ->wait();
                }
            }
        }

        $io->success('All Packages have been published.');

        return self::SUCCESS;
    }
}
