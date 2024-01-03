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
final class CopyCommand extends AbstractCommand
{
    protected static $defaultName = 'copy';

    protected function configure(): void
    {
        $this
            ->setDescription('Copies a file to each package')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addArgument('source', InputArgument::REQUIRED, 'Source file to copy')
            ->addArgument('package', InputArgument::OPTIONAL, 'Which package?')
            ->setHelp(
                <<<'HELP'
                    The <info>copy</info> command will copy whatever file you give it to all the other
                    repositories it is managing. This is useful for LICENSE files.

                    Examples:

                        <comment>%command.full_name% LICENSE</comment>

                    Read more at https://docs.sonsofphp.com/bard/
                    HELP
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $isDryRun = $input->getOption('dry-run');

        // ---
        $sourceFile = $input->getOption('working-dir') . '/' . $input->getArgument('source');
        if (!is_file($sourceFile)) {
            throw new \RuntimeException(sprintf('The file "%s" is an invalid file.', $sourceFile));
        }
        // ---

        // ---
        $bardJsonFile = new JsonFile($input->getOption('working-dir') . '/bard.json');
        foreach ($bardJsonFile->getSection('packages') as $pkg) {
            $pkgComposerFile     = realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json');
            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName             = $pkgComposerJsonFile->getSection('name');

            if (null !== $input->getArgument('package') && $pkgName !== $input->getArgument('package')) {
                continue;
            }

            $process = new Process(['cp', $sourceFile, $pkg['path']]);
            $io->text($process->getCommandLine());
            if (!$isDryRun) {
                $this->getHelper('process')->run($output, $process);
            }
        }
        // ---

        $io->success(sprintf('File "%s" has been copied to all managed repos.', $sourceFile));

        return self::SUCCESS;
    }
}
