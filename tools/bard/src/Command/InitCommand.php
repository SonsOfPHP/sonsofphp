<?php

namespace SonsOfPHP\Bard\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the initial bard.json file
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class InitCommand extends Command
{
    protected static $defaultName = 'init';
    private Json $json;

    public function __construct()
    {
        $this->json = new Json();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Creates the initial bard.json file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pwd = getenv('PWD');
        $filename = $pwd.'/bard.json';

        if (file_exists($filename)) {
            $output->writeln('bard.json file already exists');
            return COMMAND::FAILURE;
        }

        $json = $this->json->getEncoder()
            ->prettyPrint()
            ->unescapedSlashes()
            ->encode([
                'version' => '0.0.0',
                'packages' => [
                    'packages/example' => 'git@github.com:user/repo',
                ],
            ])
        ;

        $output->writeln($json);

        file_put_contents($filename, $json);

        $output->writeln(sprintf('File written to "%s"', $filename));

        return Command::SUCCESS;
    }
}
