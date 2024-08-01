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
final class AddCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('add')
            ->setDescription('Add new repo')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addArgument('path', InputArgument::REQUIRED, 'Path where code will be')
            ->addArgument('repository', InputArgument::REQUIRED, 'Repository Uri')
            ->setHelp(
                <<<'HELP'
                    The <info>add</info> command will add additional repositories that need to be managed
                    into the `bard.json` file.

                    Examples:

                        <comment>%command.full_name% src/SonsOfPHP/Bard git@github.com:vendor/package.git</comment>

                    Read more at https://docs.sonsofphp.com/bard/
                    HELP
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bardConfig = new JsonFile($input->getOption('working-dir') . '/bard.json');
        $io         = new SymfonyStyle($input, $output);
        $isDryRun   = $input->getOption('dry-run');

        // ---
        if (null === $packages = $bardConfig->getSection('packages')) {
            $packages = [];
        }
        foreach ($packages as $pkg) {
            if ($pkg['path'] === $input->getArgument('path')) {
                $io->error([
                    sprintf('It appears that the path "%s" is currently being used.', $pkg['path']),
                    'Please check your bard.json file',
                ]);
                return self::FAILURE;
            }
        }
        $packages[] = [
            'path'       => $input->getArgument('path'),
            'repository' => $input->getArgument('repository'),
        ];
        // ---

        $bardConfig = $bardConfig->setSection('packages', $packages);
        $commands = [
            ['git', 'subtree', 'add', '--prefix', $input->getArgument('path'), $input->getArgument('repository'), $input->getOption('branch'), '--squash'],
        ];
        foreach ($commands as $cmd) {
            $process = new Process($cmd);
            $io->text($process->getCommandLine());
            if (!$isDryRun) {
                $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
            }
        }

        if (!$isDryRun) {
            file_put_contents($bardConfig->getFilename(), $bardConfig->toJson());
        }

        $io->success('Package has been added.');

        return self::SUCCESS;
    }
}
