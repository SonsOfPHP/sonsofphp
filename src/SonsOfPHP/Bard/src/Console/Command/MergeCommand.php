<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\ClearSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyAuthorsSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyBranchAliasValueFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyFundingSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopySupportSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateAutoloadDevSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateAutoloadSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateProvideSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateReplaceSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateRequireDevSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Root\UpdateRequireSectionOperation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Merges composer.json files.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MergeCommand extends AbstractCommand
{
    private string $mainComposerFile;

    protected function configure(): void
    {
        $this
            ->setName('merge')
            ->setDescription('Merges package composer.json files into main composer.json file')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry Run (Do not make any changes)')
            ->addArgument('package', InputArgument::OPTIONAL, 'Which package?')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->mainComposerFile = $input->getOption('working-dir') . '/composer.json';
        if (!file_exists($this->mainComposerFile)) {
            throw new RuntimeException(sprintf('"%s" file does not exist', $this->mainComposerFile));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $isDryRun = $input->getOption('dry-run');

        $rootComposerJsonFile = new JsonFile($input->getOption('working-dir') . '/composer.json');

        // Clean out a few of the sections in root composer.json file
        $rootComposerJsonFile = $rootComposerJsonFile
            ->with(new ClearSectionOperation('autoload'))
            ->with(new ClearSectionOperation('autoload-dev'))
            ->with(new ClearSectionOperation('require'))
            ->with(new ClearSectionOperation('require-dev'))
            ->with(new ClearSectionOperation('replace'))
            ->with(new ClearSectionOperation('provide'))
        ;

        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            if (array_key_exists('config', $pkg) && array_key_exists('merge', $pkg['config']) && false === $pkg['config']['merge']) {
                // Do not merge this package
                continue;
            }

            $pkgComposerFile = realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json');
            if (!file_exists($pkgComposerFile)) {
                $output->writeln(sprintf('No "%s" found, skipping', $pkgComposerFile));
                continue;
            }

            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName             = $pkgComposerJsonFile->getSection('name');
            if (null !== $input->getArgument('package') && $pkgName !== $input->getArgument('package')) {
                continue;
            }

            $this->bardStyle->text(sprintf(
                'Merging "%s" into root composer.json',
                $pkgComposerJsonFile->getSection('name'),
            ));

            // Update root composer.json
            $rootComposerJsonFile = $rootComposerJsonFile
                ->with(new UpdateReplaceSectionOperation($pkgComposerJsonFile))
                ->with(new UpdateRequireSectionOperation($pkgComposerJsonFile))
                ->with(new UpdateRequireDevSectionOperation($pkgComposerJsonFile))
                ->with(new UpdateAutoloadSectionOperation($pkgComposerJsonFile))
                ->with(new UpdateAutoloadDevSectionOperation($pkgComposerJsonFile))
                ->with(new UpdateProvideSectionOperation($pkgComposerJsonFile))
            ;

            // Update package composer.json
            $pkgComposerJsonFile = $pkgComposerJsonFile
                ->with(new CopyBranchAliasValueFromRootToPackageOperation($rootComposerJsonFile))
                ->with(new CopySupportSectionFromRootToPackageOperation($rootComposerJsonFile))
                ->with(new CopyAuthorsSectionFromRootToPackageOperation($rootComposerJsonFile))
                ->with(new CopyFundingSectionFromRootToPackageOperation($rootComposerJsonFile))
            ;

            if (!$isDryRun) {
                $pkgComposerJsonFile->save();
                $io->text(sprintf('Updated "%s"', $pkgComposerJsonFile->getRealPath()));
            }
        }

        if (!$isDryRun) {
            $rootComposerJsonFile->save();
            $io->text(sprintf('Updated "%s"', $rootComposerJsonFile->getRealPath()));
        }

        // @todo if not dry-run, run composer dump

        $io->success('Merge Complete');

        return self::SUCCESS;
    }
}
