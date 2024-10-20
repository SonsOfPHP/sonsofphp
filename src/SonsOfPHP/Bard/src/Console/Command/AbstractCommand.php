<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\JsonFileInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCommand extends Command
{
    protected JsonFileInterface $bardConfig;

    protected SymfonyStyle $bardStyle;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->initializeBardStyle($input, $output);
        $this->initializeBardConfig($input, $output);
    }

    private function initializeBardConfig(InputInterface $input, OutputInterface $output): void
    {
        $bardJson = $input->getOption('working-dir') . '/bard.json';
        $config   = $input->getOption('config');
        if ('bard.json' !== $config) {
            $bardJson = $config;
        }

        if ($output->isDebug()) {
            $this->bardStyle->text(sprintf('Using configuration file "%s"', $bardJson));
        }

        $this->bardConfig = new JsonFile($bardJson);
    }

    private function initializeBardStyle(InputInterface $input, OutputInterface $output): void
    {
        $this->bardStyle = new SymfonyStyle($input, $output);
    }

    protected function getFormatterHelper(): FormatterHelper
    {
        return $this->getHelper('formatter');
    }

    protected function getProcessHelper(): ProcessHelper
    {
        return $this->getHelper('process');
    }
}
