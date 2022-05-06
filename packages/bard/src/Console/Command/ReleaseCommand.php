<?php

namespace SonsOfPHP\Bard\Console\Command;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Manipulator\Composer\UpdateReplaceSectionInRootComposer;
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
    private $releaseVersion;

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
            ->addArgument('release', InputArgument::REQUIRED, 'Use format <major>.<minor>.<patch>-<PreRelease>+<BuildMetadata> or "major", "minor", "patch"')
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
        $version = $input->getArgument('release');
        if (in_array($version, ['major', 'minor', 'patch'])) {
            $bardConfig = new JsonFile($input->getOption('working-dir').'/bard.json');
            $currentVersion = new Version($bardConfig->getSection('version'));

            switch ($version) {
                case 'major':
                    $this->releaseVersion = $currentVersion->nextMajor();
                    break;
                case 'minor':
                    $this->releaseVersion = $currentVersion->nextMinor();
                    break;
                case 'patch':
                    $this->releaseVersion = $currentVersion->nextPatch();
                    break;
            }
        } else {
            $this->releaseVersion = new Version($version);
        }
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
        $this->formatter = $this->getHelper('formatter');
        $bardConfig = new JsonFile($input->getOption('working-dir').'/bard.json');

        if ($input->getOption('dry-run')) {
            $output->writeln($this->formatter->formatBlock('dry-run enabled no changes will be made', 'info', true));
        }

        $output->writeln($this->formatter->formatSection('bard', sprintf('Current version <info>%s</info>', $bardConfig->getSection('version')), 'info'));

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

        // 4. Subtree Split for each package
        // git subtree split --prefix packages/{component} -b {component/name}
        // git push {repo} {component/name}:{remote-branch}
        // git branch -D {component/name}

        // 5. Update branch alias in all composer.json files

        // 6. Next dev release
        // - Update composer.json file

        // 7. Commit and push updates
        // git add .
        // git commit -m 'starting new version'
        // git push origin {branch}

        if ($input->getOption('dry-run')) {
            $output->writeln($this->formatter->formatBlock('no changes were made', 'info', true));
        }
        return self::SUCCESS;
    }
}
