<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the initial bard.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InitCommand extends AbstractCommand
{
    protected static $defaultName = 'init';

    /**
     * {@inheritDoc}
     */
    // public function __construct()
    // {
    //    parent::__construct();
    // }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates the initial bard.json file')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getOption('working-dir').'/bard.json';

        if (file_exists($filename)) {
            $output->writeln('bard.json file already exists');

            return self::FAILURE;
        }

        touch($filename);

        $bardJsonFile = new JsonFile($filename);
        $bardJsonFile = $bardJsonFile->setSection('version', '0.0.0');
        $bardJsonFile = $bardJsonFile->setSection('packages', [
            ['path' => 'packages/component', 'repository' => 'git@github.com/org/component'],
            ['path' => 'packages/component', 'repository' => 'git@github.com/org/component'],
            ['path' => 'packages/component', 'repository' => 'git@github.com/org/component'],
        ]);

        $output->writeln($bardJsonFile->toJson());

        file_put_contents($bardJsonFile->getFilename(), $bardJsonFile->toJson());

        $output->writeln(sprintf('File written to "%s"', $filename));

        return self::SUCCESS;
    }
}
