<?php

namespace SonsOfPHP\Bard\Command;

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
            throw new \RunTimeException(sprintf('"%s" file does not exist', $bardConfigFile));
        }

        $this->bardConfig = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($bardConfigFile));

        $this->mainComposerFile = $input->getOption('working-dir').'/composer.json';
        if (!file_exists($this->mainComposerFile)) {
            throw new \RunTimeException(sprintf('"%s" file does not exist', $this->mainComposerFile));
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
        // Purge main composer.json sections?
        // replace
        // require, require-dev
        // autoload, autoload-dev
        // repositories
        // extra
        $packageNames = [];
        foreach ($this->bardConfig['packages'] as $dir => $repo) {
            $packageComposerFile = $input->getOption('working-dir').'/'.$dir.'/composer.json';
            if (!file_exists($packageComposerFile)) {
                $output->writeln(sprintf('No "%s" found, skipping', $packageComposerFile));
                continue;
            }

            $packageComposerConfig = $this->json->getDecoder()->objectAsArray()
                ->decode(file_get_contents($packageComposerFile));
            $output->writeln($this->formatter->formatSection('bard', sprintf('Working on "%s"', $packageComposerConfig['name'])));
            $packageNames[] = $packageComposerConfig['name'];

            //###> Update "replace" in main
            $this->mainComposerConfig['replace'][$packageComposerConfig['name']] = 'self.version';
            //###<

            //###> merge pkg "require" to main "require"
            if (isset($packageComposerConfig['require'])) {
                foreach ($packageComposerConfig['require'] as $pkg => $ver) {
                    // @todo check versions
                    $this->mainComposerConfig['require'][$pkg] = $ver;
                }
            }
            //###<

            //###> merge pkg "require-dev" to main "require-dev"
            //var_dump($packageComposerJson['require']);
            if (isset($packageComposerConfig['require-dev'])) {
                foreach ($packageComposerConfig['require-dev'] as $pkg => $ver) {
                    // @todo check versions
                    $this->mainComposerConfig['require-dev'][$pkg] = $ver;
                }
            }
            //###<

            //###> autoload => pkg to main
            $pathPrefix = str_replace('/composer.json', '', str_replace($input->getOption('working-dir').'/', '', $packageComposerFile));
            foreach ($packageComposerConfig['autoload'] as $section => $sectionConfig) {
                if ($section === 'psr-4') {
                    foreach ($sectionConfig as $namespace => $path) {
                        $this->mainComposerConfig['autoload']['psr-4'][$namespace] = $pathPrefix.$path;
                    }
                }

                if ($section === 'exclude-from-classmap') {
                    foreach ($sectionConfig as $path) {
                        $this->mainComposerConfig['autoload']['exclude-from-classmap'][] = $pathPrefix.$path;
                    }
                }
            }
            if (isset($this->mainComposerConfig['autoload']['psr-4'])) {
                $this->mainComposerConfig['autoload']['psr-4'] = array_unique($this->mainComposerConfig['autoload']['psr-4']);
            }
            if (isset($this->mainComposerConfig['autoload']['exclude-from-classmap'])) {
                $this->mainComposerConfig['autoload']['exclude-from-classmap'] = array_unique($this->mainComposerConfig['autoload']['exclude-from-classmap']);
            }
            //###<

            //###> autoload-dev => pkg to main
            //###<

            //###> provide => pkg to main
            if (isset($packageComposerConfig['provide'])) {
                foreach ($packageComposerConfig['provide'] as $provide => $provideVersion) {
                    $this->mainComposerConfig['provide'][$provide] = $provideVersion;
                }
                $this->mainComposerConfig['provide'] = array_unique($this->mainComposerConfig['provide']);
            }
            //###<

            //###> ?? authors => main to pkg
            //###<

            //###> support => main to pkg
            //###<

            //###> ?? extra.branch-alias => main to pkg
            //###<

            //###> ?? suggest => pkg to main
            //###<

            // save pkg composer.json
        }

        //###> Clean main componser config "require" if packages are already included
        $this->mainComposerConfig['require'] = array_diff_key($this->mainComposerConfig['require'], array_flip($packageNames));
        //###<

        file_put_contents(
            $this->mainComposerFile,
            $this->json->getEncoder()
                ->prettyPrint()
                ->unescapedSlashes()
                ->encode($this->mainComposerConfig)
        );

        $output->writeln('complete');
        return self::SUCCESS;
    }
}
