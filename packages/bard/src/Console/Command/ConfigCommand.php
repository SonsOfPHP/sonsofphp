<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Configure bard.json file
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ConfigCommand extends AbstractCommand
{
    protected static $defaultName = 'config';
    private Json $json;

    /**
     * {@inheritdoc}
     */
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
            ->setDescription('')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('@todo');
        return self::SUCCESS;
    }
}
