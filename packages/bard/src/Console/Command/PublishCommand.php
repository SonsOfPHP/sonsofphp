<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Publish Command.
 *
 * Push up changes to all package repos
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class PublishCommand extends AbstractCommand
{
    protected static $defaultName = 'publish';

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Push changes to package repos')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardConfig = new JsonFile($input->getOption('working-dir').'/bard.json');
        $formatter = $this->getHelper('formatter');
        $io = new SymfonyStyle($input, $output);

        foreach ($bardConfig->getSection('packages') as $pkg) {
            $pkgComposerFile = realpath($input->getOption('working-dir').'/'.$pkg['path'].'/composer.json');
            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName = $pkgComposerJsonFile->getSection('name');
            $io->text(sprintf('Pushing <info>%s</>', $pkgName));

            $commands = [
                // subtree push
                ['git', 'subtree', 'push', '-P', $pkg['path'], $pkg['repository'], $input->getOption('branch')],
                // -- OR --
                // subtree split
                // ['git', 'subtree', 'split', '-P', $pkg['path'], '-b', $pkgName],
                // ['git', 'checkout', $pkgName],
                // ['git', 'push', $pkg['repository'], sprintf('%s:%s', $pkgName, $input->getOption('branch'))],
                // ['git', 'checkout', $input->getOption('branch')],
                // ['git', 'branch', '-D', $pkgName],
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
