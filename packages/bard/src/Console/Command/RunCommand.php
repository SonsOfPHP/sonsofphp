<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Use to run a command within each package directory.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class RunCommand extends AbstractCommand
{
    protected static $defaultName = 'run';
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
