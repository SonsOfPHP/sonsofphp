<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Creates the initial bard.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InitCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('init')
            ->setDescription('Creates the initial bard.json file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $filename = $input->getOption('working-dir') . '/bard.json';

        if (file_exists($filename)) {
            $io->error(sprintf('%s/bard.json file already exists', $input->getOption('working-dir')));

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

        $io->text($bardJsonFile->toJson());

        file_put_contents($bardJsonFile->getFilename(), $bardJsonFile->toJson());

        $output->writeln(sprintf('File written to "%s"', $filename));

        return self::SUCCESS;
    }
}
