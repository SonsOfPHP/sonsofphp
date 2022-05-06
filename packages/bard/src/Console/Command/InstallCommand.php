<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Component\Json\Json;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InstallCommand extends AbstractCommand
{
    protected static $defaultName = 'install';
    private Json $json;
    private array $bardConfig;
    private $formatter;

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
            ->setDescription('Runs composer install for all packages')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $bardConfigFile = $input->getOption('working-dir').'/bard.json';
        if (!file_exists($bardConfigFile)) {
            throw new \RuntimeException(sprintf('"%s" file does not exist', $bardConfigFile));
        }

        $this->bardConfig = $this->json->getDecoder()->objectAsArray()
            ->decode(file_get_contents($bardConfigFile));

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->formatter = $this->getHelper('formatter');
        $output->writeln($this->formatter->formatSection('bard', 'Searching for composer.json files'));
        $rootDir = $input->getOption('working-dir');
        $finder = new Finder();
        $finder->files()->name('composer.json')
            ->ignoreVCS(true)
            ->notPath('vendor');
        foreach ($this->bardConfig['packages'] as $pkgLocation) {
            $output->writeln($this->formatter->formatSection('bard', sprintf('Looking in "%s"', $pkgLocation)));
            $finder->in($rootDir.'/'.$pkgLocation);
        }

        foreach ($finder as $file) {
            $output->writeln($this->formatter->formatBlock(sprintf('Working in "%s"', $file->getPath()), 'info', true));
            $process = new Process([
                'composer',
                'install',
                '--optimize-autoloader',
                '--no-progress',
                '--no-interaction',
                '--ansi',
                '--working-dir',
                $file->getPath(),
            ]);
            $output->writeln($this->formatter->formatSection('exec', $process->getCommandLine(), 'comment'));
            $this->getHelper('process')->run($output, $process);
        }

        return self::SUCCESS;
    }
}
