<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\File\Composer\Package\Authors;
use SonsOfPHP\Bard\Worker\File\Composer\Package\BranchAlias;
use SonsOfPHP\Bard\Worker\File\Composer\Package\Funding;
use SonsOfPHP\Bard\Worker\File\Composer\Package\Support;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateAutoloadDevSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateAutoloadSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateProvideSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateReplaceSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateRequireDevSection;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateRequireSection;
use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Helper\HelperInterface;
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
    protected static $defaultName = 'merge';
    private readonly Json $json;
    private array $bardConfig;
    private string $mainComposerFile;
    private ?HelperInterface $formatter = null;

    public function __construct()
    {
        $this->json = new Json();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
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

        $this->bardConfig = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($bardConfigFile));

        $this->mainComposerFile = $input->getOption('working-dir') . '/composer.json';
        if (!file_exists($this->mainComposerFile)) {
            throw new RuntimeException(sprintf('"%s" file does not exist', $this->mainComposerFile));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $isDryRun = $input->getOption('dry-run');

        $this->formatter = $this->getHelper('formatter');

        $rootComposerJsonFile = new JsonFile($input->getOption('working-dir') . '/composer.json');

        // Clean out a few of the sections in root composer.json file
        $rootComposerJsonFile = $rootComposerJsonFile->setSection('autoload', []);
        $rootComposerJsonFile = $rootComposerJsonFile->setSection('autoload-dev', []);

        foreach ($this->bardConfig['packages'] as $pkg) {
            $pkgComposerFile = realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json');
            if (!file_exists($pkgComposerFile)) {
                $output->writeln(sprintf('No "%s" found, skipping', $packageComposerFile));
                continue;
            }

            $pkgComposerJsonFile = new JsonFile($pkgComposerFile);
            $pkgName             = $pkgComposerJsonFile->getSection('name');
            if (null !== $input->getArgument('package') && $pkgName !== $input->getArgument('package')) {
                continue;
            }

            $output->writeln($this->formatter->formatSection('bard', sprintf('Merging "%s" into root composer.json', $pkgComposerJsonFile->getSection('name'))));

            // Update root composer.json
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateReplaceSection($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireSection($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireDevSection($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadSection($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadDevSection($pkgComposerJsonFile));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateProvideSection($pkgComposerJsonFile));
            // $rootComposerJsonFile = $rootComposerJsonFile->with(new Conflict($pkgComposerJsonFile));

            // Update package composer.json
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new BranchAlias($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new Support($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new Authors($rootComposerJsonFile));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new Funding($rootComposerJsonFile));

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
