<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateAutoloadDevSectionInRootComposer;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateAutoloadSectionInRootComposer;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateProvideSectionInRootComposer;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateReplaceSectionInRootComposer;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateRequireDevSectionInRootComposer;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateRequireSectionInRootComposer;
use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Merges composer.json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MergeCommand extends AbstractCommand
{
    protected static $defaultName = 'merge';
    private Json $json;
    private array $bardConfig;
    private string $mainComposerFile;
    private array $mainComposerConfig;
    private $formatter;

    public function __construct()
    {
        $this->json = new Json();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            // options for dry-run, by default it should be a dry-run
            ->setDescription('Merges package composer.json files into main composer.json file')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $bardConfigFile = $input->getOption('working-dir').'/bard.json';
        if (!file_exists($bardConfigFile)) {
            throw new \RuntimeException(sprintf('"%s" file does not exist', $bardConfigFile));
        }

        $this->bardConfig = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($bardConfigFile));

        $this->mainComposerFile = $input->getOption('working-dir').'/composer.json';
        if (!file_exists($this->mainComposerFile)) {
            throw new \RuntimeException(sprintf('"%s" file does not exist', $this->mainComposerFile));
        }

        $this->mainComposerConfig = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($this->mainComposerFile));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->formatter = $this->getHelper('formatter');

        $rootComposerJsonFile = new JsonFile($input->getOption('working-dir').'/composer.json');

        foreach ($this->bardConfig['packages'] as $dir) {
            die(var_dump($dir));
            $packageComposerFile = realpath($input->getOption('working-dir').'/'.$dir.'/composer.json');
            if (!file_exists($packageComposerFile)) {
                $output->writeln(sprintf('No "%s" found, skipping', $packageComposerFile));
                continue;
            }

            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir').'/'.$dir.'/composer.json'));

            $output->writeln($this->formatter->formatSection('bard', sprintf('Merging "%s" into root composer.json', $pkgComposerJsonFile->getSection('name'))));

            //$output->writeln($this->formatter->formatSection('replace', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateReplaceSectionInRootComposer(), $pkgComposerJsonFile);

            //$output->writeln($this->formatter->formatSection('require', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireSectionInRootComposer(), $pkgComposerJsonFile);

            //$output->writeln($this->formatter->formatSection('require-dev', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateRequireDevSectionInRootComposer(), $pkgComposerJsonFile);

            //$output->writeln($this->formatter->formatSection('autoload', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadSectionInRootComposer(), $pkgComposerJsonFile);

            //$output->writeln($this->formatter->formatSection('autoload-dev', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateAutoloadDevSectionInRootComposer(), $pkgComposerJsonFile);

            //$output->writeln($this->formatter->formatSection('provide', 'pkg => root', 'comment'));
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateProvideSectionInRootComposer(), $pkgComposerJsonFile);
        }

        file_put_contents($rootComposerJsonFile->getFilename(), $rootComposerJsonFile->toJson());

        $output->writeln('complete');

        return self::SUCCESS;
    }
}
