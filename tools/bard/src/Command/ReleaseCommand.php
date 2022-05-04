<?php

namespace SonsOfPHP\Bard\Command;

use SonsOfPHP\Component\Json\Json;
use SonsOfPHP\Component\Version\Version;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Release command will take in user input and call the other release commands
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReleaseCommand extends AbstractCommand
{
    protected static $defaultName = 'release';
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
            ->setDescription('Create a new release')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute a dry-run with nothing being updated or released')
            ->addOption('patch', null, InputOption::VALUE_NONE, 'Create realase for a patch version')
            ->addOption('minor', null, InputOption::VALUE_NONE, 'Create realase for a minor version')
            ->addOption('major', null, InputOption::VALUE_NONE, 'Create realase for a major version')
            ->addOption('pre-release', null, InputOption::VALUE_REQUIRED, 'Attach Pre-release Data')
            ->addOption('build-metadata', null, InputOption::VALUE_REQUIRED, 'Attach Build Metadata')
            ->setHelp(
                <<<EOT
This command allows you to create a new release and will update the various
repos that have been configured. The current version can be found in the
`bard.json` file. This will will update the version based on the type of release
that you are doing.

    <comment>%command.full_name%</comment>

Read more at https://docs.SonsOfPHP.com
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $bardConfigFile = $input->getOption('working-dir').'/bard.json';
        if (!file_exists($bardConfigFile)) {
            throw new \RunTimeException(sprintf('"%s" file does not exist', $bardConfigFile));
        }

        $this->bardConfig = $this->json->getDecoder()->objectAsArray()
            ->decode(file_get_contents($bardConfigFile));

        $this->formatter = $this->getHelper('formatter');
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('dry-run')) {
            $output->writeln($this->formatter->formatBlock('dry-run enabled no changes will be made', 'info', true));
        }
        $output->writeln($this->formatter->formatSection('bard', sprintf('Current version <info>%s</info>', $this->bardConfig['version']), 'info'));

        // 1. Update "replace" in main composer.json with the Package Names
        // "package/name": "self.version"

        // 2. Update Changelog
        // Changes the "Unreleased" headline to the version we are releaseing
        // Adds new headline at top for unreleased features

        // 3. Tag Release and push
        // git add .
        // git commit -m 'prepare release {version}'
        // git push origin {branch}
        // git tag {version}
        // git push --tags

        // 4. Update branch alias in composer.json files

        // 5. Next dev release
        // - Update composer.json file
        $commands = [
            // Add All Files
            'git add .',
            // Create a Commit with new version
            sprintf('git commit --allow-empty -m "open %s"', $this->bardConfig['version']),
            // Push up commit
            sprintf('git push origin %s', 'main'),
        ];
        foreach ($commands as $cmd) {
            $output->writeln($this->formatter->formatSection('exec', $cmd, 'comment'));
        }

        if ($input->getOption('dry-run')) {
            $output->writeln($this->formatter->formatBlock('no changes were made', 'info', true));
        }
        return self::SUCCESS;
    }
}
