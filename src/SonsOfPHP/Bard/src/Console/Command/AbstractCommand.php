<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
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
    protected JsonFile $bardConfig;

    protected SymfonyStyle $bardStyle;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->bardConfig = new JsonFile($input->getOption('working-dir') . '/bard.json');
        $config = $input->getOption('config');
        if ('bard.json' !== $config) {
            $this->bardConfig = new JsonFile($config);
        }

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
