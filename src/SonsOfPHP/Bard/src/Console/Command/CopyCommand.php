<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CopyCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('copy')
            ->setDescription('Copies a file to each package')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addOption('overwrite', null, InputOption::VALUE_NONE, 'If file exists, overwrite it')
            ->addArgument('source', InputArgument::REQUIRED, 'Source file to copy')
            ->addArgument('package', InputArgument::OPTIONAL, 'Which package?')
            ->addUsage('LICENSE')
            ->addUsage('LICENSE sonsofphp/bard')
            ->addUsage('--overwrite LICENSE sonsofphp/bard')
            ->setHelp(
                <<<'HELP'
The <info>copy</info> command will copy whatever file you give it to all the
other repositories it is managing. This is useful for LICENSE files.

Examples:

    <comment>%command.full_name% LICENSE</comment>

Read more at <href=https://docs.sonsofphp.com/bard/>https://docs.sonsofphp.com/bard/</>
HELP
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isDryRun = $input->getOption('dry-run');

        // ---
        $sourceFile = $input->getOption('working-dir') . '/' . $input->getArgument('source');
        if (!is_file($sourceFile)) {
            throw new RuntimeException(sprintf('The file "%s" is an invalid file.', $sourceFile));
        }

        // ---

        // ---
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgPath = realpath($input->getOption('working-dir') . '/' . $pkg['path']);
            $pkgFile = new JsonFile($pkgPath . '/composer.json');
            $pkgName = $pkgFile->getSection('name');

            if (null !== $input->getArgument('package') && $pkgName !== $input->getArgument('package')) {
                continue;
            }

            $doesFileExists = file_exists($pkgPath . '/' . $input->getArgument('source'));

            if (
                (($doesFileExists && true === $input->getOption('overwrite')) || false === $doesFileExists)
                && !$isDryRun
            ) {
                $process = new Process(['cp', $sourceFile, $pkgPath]);
                //$worker = (new CopyFileWorker($source))->apply($pkg['path']);
                $this->getProcessHelper()->run($output, $process);
            }

            $message = match ($doesFileExists) {
                true => match ($input->getOption('overwrite')) {
                    true => sprintf(
                        'Updated "%s/%s"',
                        $pkg['path'],
                        $input->getArgument('source'),
                    ),
                    false => sprintf(
                        'File Exists "%s/%s" and has not been updated',
                        $pkg['path'],
                        $input->getArgument('source'),
                    ),
                },
                false => sprintf(
                    'Copied "%s" to "%s"',
                    $input->getArgument('source'),
                    $pkg['path']
                ),
            };

            $style = match ($doesFileExists) {
                false => 'fg=white',
                true  => match ($input->getOption('overwrite')) {
                    false => 'fg=red',
                    true  => 'fg=green',
                },
            };

            $this->bardStyle->text($this->getFormatterHelper()->formatSection($pkgName, $message, $style));
        }

        // ---

        $this->bardStyle->success(sprintf('File "%s" has been copied to all managed repos.', $sourceFile));

        if ($isDryRun) {
            $this->bardStyle->info('Dry-run enabled, nothing was modified');
        }

        return self::SUCCESS;
    }
}
