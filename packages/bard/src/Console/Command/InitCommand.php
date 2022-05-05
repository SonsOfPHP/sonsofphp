<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the initial bard.json file
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InitCommand extends AbstractCommand
{
    protected static $defaultName = 'init';
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
            ->setDescription('Creates the initial bard.json file')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getOption('working-dir').'/bard.json';
        if (file_exists($configFile)) {
            throw new \RunTimeException(sprintf('"%s" file already exists', $configFile));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getOption('working-dir').'/bard.json';

        if (file_exists($filename)) {
            $output->writeln('bard.json file already exists');
            return self::FAILURE;
        }

        $json = $this->json->getEncoder()
            ->prettyPrint()
            ->unescapedSlashes()
            ->encode([
                'name' => 'example/example',
                'version' => '0.0.0',
                'packages' => [
                    'packages/example/',
                ],
            ])
        ;

        $output->writeln($json);

        file_put_contents($filename, $json);

        $output->writeln(sprintf('File written to "%s"', $filename));

        return self::SUCCESS;
    }
}
