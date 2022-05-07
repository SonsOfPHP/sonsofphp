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
use Symfony\Component\Console\Style\SymfonyStyle;
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
    private JsonFile $bardConfig;
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
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addArgument('release', InputArgument::REQUIRED, 'Next Release you want to start? Use format <major>.<minor>.<patch>-<PreRelease>+<BuildMetadata> or "major", "minor", "patch"')
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
        $version          = $input->getArgument('release');
        $this->bardConfig = new JsonFile($input->getOption('working-dir').'/bard.json');
        $currentVersion   = new Version($this->bardConfig->getSection('version'));

        if (in_array($version, ['major', 'minor', 'patch'])) {
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

        if ($currentVersion->isGreaterThan($this->releaseVersion)) {
            throw new \RuntimeException('Cannot release a lower version');
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
        $formatter = $this->getHelper('formatter');
        $io = new SymfonyStyle($input, $output);
        if ($input->getOption('dry-run')) {
            $io->note('dry-run enabled no changes will be made');
        }
        $io->text([
            sprintf('Bumping release from <info>%s</> to <info>%s</>', $this->bardConfig->getSection('version'), $this->releaseVersion->toString()),
        ]);

        $rootComposerJsonFile = new JsonFile($input->getOption('working-dir').'/composer.json');

        // 1. Update "replace" in main composer.json with the Package Names
        // "package/name": "self.version"
        $io->section('updating root composer.json "replace" section with package information');
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile  = new JsonFile(realpath($input->getOption('working-dir').'/'.$pkg['path'].'/composer.json'));
            $output->writeln([
                $formatter->formatSection($pkgComposerJsonFile->getSection('name'), 'Updating root <info>composer.json</info>'),
            ]);
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateReplaceSectionInRootComposer(), $pkgComposerJsonFile);
        }
        $output->writeln([
            'saving root composer.json'
        ]);
        if (!$input->getOption('dry-run')) {
            file_put_contents($rootComposerJsonFile->getFilename(), $rootComposerJsonFile->toJson());
        }
        $output->writeln([
            'root composer.json file saved'
        ]);
        $io->newLine();
        $io->success('root "composer.json" update complete');

        // @todo
        // 2. Update Changelog
        // Changes the "Unreleased" headline to the version we are releaseing
        // Adds new headline at top for unreleased features

        // 3. Tag Release and push
        $io->newLine();
        $io->section(sprintf('updating mother repo for release %s', $this->releaseVersion->toString()));
        $process = new Process(['git', 'add', '.']);
        $io->text($process->getCommandLine());
        if (!$input->getOption('dry-run')) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $process = new Process(['git', 'commit', '-m', sprintf('"Preparing for Release v%s"', $this->releaseVersion->toString())]);
        $io->text($process->getCommandLine());
        if (!$input->getOption('dry-run')) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $process = new Process(['git', 'push', 'origin', $input->getOption('branch')]);
        $io->text($process->getCommandLine());
        if (!$input->getOption('dry-run')) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $process = new Process(['git', 'tag', 'v'.$this->releaseVersion->toString()]);
        $io->text($process->getCommandLine());
        if (!$input->getOption('dry-run')) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $process = new Process(['git', 'push', 'origin', 'v'.$this->releaseVersion->toString()]);
        $io->text($process->getCommandLine());
        if (!$input->getOption('dry-run')) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }
        $io->success('Mother Repository Released');

        // 4. Subtree Split for each package
        $io->newLine();
        $io->title(sprintf('updating package repos with release %s', $this->releaseVersion->toString()));
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir').'/'.$pkg['path'].'/composer.json'));
            $pkgName = $pkgComposerJsonFile->getSection('name');
            $io->text([
                sprintf('Package <info>%s</> is being released', $pkgName),
                sprintf('git subtree split --prefix %s -b %s', $pkg['path'], $pkgName),
                sprintf('git checkout %s', $pkgName),
                sprintf('git push %s %s:%s', $pkg['repository'], $pkgName, $input->getOption('branch')),
                sprintf('git tag %s_%s', $pkgName, $this->releaseVersion->toString()),
                sprintf('git push %s %s_%s:v%s', $pkg['repository'], $pkgName, $this->releaseVersion->toString(), $this->releaseVersion->toString()),
                sprintf('git checkout %s', $input->getOption('branch')),
                sprintf('git branch -D %s', $pkgName),
            ]);
            $io->newLine();
        }
        $io->success('All Packages have been Released');

        // 5. Update branch alias in all composer.json files
        $io->section('Updating Branch Alias in root and packages');
        $io->success('All files have been saved');

        // 6. Next dev release
        // Update composer.json file

        // 7. Commit and push updates
        // git add .
        // git commit -m 'starting new version'
        // git push origin {branch}

        $io->newLine();
        $io->success('Congrations on your new release');
        if ($input->getOption('dry-run')) {
            $io->note([
                'dry-run was enabled so no files were changed and no code was pushed'
            ]);
        }

        return self::SUCCESS;
    }
}
