<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\Operation\Bard\AddPackageOperation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
            ->setDescription('Add read-only repo')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addArgument('path', InputArgument::REQUIRED, 'Path where code will be')
            ->addArgument('repository', InputArgument::REQUIRED, 'Repository Uri')
            ->addUsage('src/SonsOfPHP/Bard git@github.com:SonsOfPHP/bard.git')
            ->addUsage('--branch=master src/SonsOfPHP/Bard git@github.com:SonsOfPHP/bard.git')
            ->addUsage('--dry-run src/SonsOfPHP/Bard git@github.com:SonsOfPHP/bard.git')
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
        $this->bardConfig = $this->bardConfig->with(new AddPackageOperation([
            'path'       => $input->getArgument('path'),
            'repository' => $input->getArgument('repository'),
        ]));

        $isDryRun = $input->getOption('dry-run');
        $process = new Process([
            'git',
            'subtree',
            'add',
            '--prefix',
            $input->getArgument('path'),
            $input->getArgument('repository'),
            $input->getOption('branch'),
            '--squash',
        ]);

        if ($this->bardStyle->isDebug()) {
            $this->bardStyle->block($process->getCommandLine(), 'CMD');
        }

        if (!$isDryRun) {
            $this->getProcessHelper()->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        if (!$isDryRun) {
            $this->bardConfig->save();
        }

        $this->bardStyle->success('Package has been added.');

        if ($isDryRun) {
            $this->bardStyle->info('Dry-run enabled, bard config file was not updated');
        }

        return self::SUCCESS;
    }
}
