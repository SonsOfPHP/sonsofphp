<?php

namespace SonsOfPHP\Bard\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Merges composer.json files
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MergeCommand extends Command
{
    protected static $defaultName = 'merge';
    private Json $json;

    public function __construct()
    {
        $this->json = new Json();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // options for dry-run, by default it should be a dry-run
            ->setHelp('Merges package composer.json files into main composer.json file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pwd = getenv('PWD');
        $bardJsonFile = $pwd.'/bard.json';
        $mainComposerJsonFile = $pwd.'/composer.json';

        if (!file_exists($bardJsonFile)) {
            $output->writeln(sprintf('"%s" file does not exist', $bardJsonFile));
            return COMMAND::FAILURE;
        }

        if (!file_exists($mainComposerJsonFile)) {
            $output->writeln(sprintf('"%s" file does not exist', $composerJsonFile));
            return COMMAND::FAILURE;
        }

        $bardJson = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($bardJsonFile));

        $mainComposerJson = $this->json->getDecoder()
            ->objectAsArray()
            ->decode(file_get_contents($mainComposerJsonFile));
        // Purge main composer.json sections?
        // replace
        // require, require-dev
        // autoload, autoload-dev
        // repositories
        // extra

        foreach ($bardJson['packages'] as $dir => $repo) {
            $packageComposerJsonFile = $pwd.'/'.$dir.'/composer.json';
            if (!file_exists($packageComposerJsonFile)) {
                $output->writeln(sprintf('No "%s" found', $packageComposerJsonFile));
                continue;
            }
            $packageComposerJson = $this->json->getDecoder()
                ->objectAsArray()
                ->decode(file_get_contents($packageComposerJsonFile));

            //###> Update "replace" in main
            $mainComposerJson['replace'][$packageComposerJson['name']] = 'self.version';
            //###<

            //###> merge pkg "require" to main "require"
            if (isset($packageComposerJson['require'])) {
                foreach ($packageComposerJson['require'] as $pkg => $ver) {
                    // @todo check versions
                    $mainComposerJson['require'][$pkg] = $ver;
                }
            }
            //###<

            //###> merge pkg "require-dev" to main "require-dev"
            //var_dump($packageComposerJson['require']);
            if (isset($packageComposerJson['require-dev'])) {
                foreach ($packageComposerJson['require-dev'] as $pkg => $ver) {
                    // @todo check versions
                    $mainComposerJson['require-dev'][$pkg] = $ver;
                }
            }
            //###<

            //###> autoload => pkg to main
            //###<

            //###> autoload-dev => pkg to main
            //###<

            //###> provide => pkg to main
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

        file_put_contents($mainComposerJsonFile,
            $this->json->getEncoder()
                ->prettyPrint()
                ->unescapedSlashes()
                ->encode($mainComposerJson)
        );

        return Command::SUCCESS;
    }
}
