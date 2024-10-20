<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\ClearSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\UpdateAuthorsSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\UpdateBranchAliasSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\UpdateFundingSectionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\UpdateSupportSectionOperation;
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
    protected JsonFile $bardConfig;

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
        $bardConfigFile = $input->getOption('working-dir') . '/bard.json';
        if (!file_exists($bardConfigFile)) {
            throw new RuntimeException(sprintf('"%s" file does not exist', $bardConfigFile));
        }

        $this->bardConfig = new JsonFile($bardConfigFile);

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
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('autoload'));
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('autoload-dev'));
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('require'));
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('require-dev'));
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('replace'));
        $rootComposerJsonFile = $rootComposerJsonFile->with(new ClearSectionOperation('provide'));

        foreach ($this->bardConfig->getSection('packages') as $pkg) {
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

            $output->writeln($this->getFormatterHelper()->formatSection('bard', sprintf('Merging "%s" into root composer.json', $pkgComposerJsonFile->getSection('name'))));

            // Update root composer.json
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateReplaceSectionOperation($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireSectionOperation($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireDevSectionOperation($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadSectionOperation($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadDevSectionOperation($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateProvideSectionOperation($pkgComposerJsonFile));
            // $rootComposerJsonFile = $rootComposerJsonFile->with(new Conflict($pkgComposerJsonFile));

            // Update package composer.json
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new UpdateBranchAliasSectionOperation($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new UpdateSupportSectionOperation($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new UpdateAuthorsSectionOperation($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new UpdateFundingSectionOperation($rootComposerJsonFile));

            if (!$isDryRun) {
                file_put_contents($pkgComposerJsonFile->getFilename(), $pkgComposerJsonFile->toJson());
                $io->text(sprintf('Updated "%s"', $pkgComposerJsonFile->getFilename()));
            }
        }

        if (!$isDryRun) {
            file_put_contents($rootComposerJsonFile->getFilename(), $rootComposerJsonFile->toJson());
            $io->text(sprintf('Updated "%s"', $rootComposerJsonFile->getFilename()));
        }

        $io->success('Merge Complete');

        return self::SUCCESS;
    }
}
