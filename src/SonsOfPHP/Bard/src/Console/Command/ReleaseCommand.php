<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\File\Bard\UpdateVersion;
use SonsOfPHP\Bard\Worker\File\Composer\Root\UpdateReplaceSection;
use SonsOfPHP\Component\Json\Json;
use SonsOfPHP\Component\Version\Version;
use SonsOfPHP\Component\Version\VersionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Release command will take in user input and call the other release commands.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReleaseCommand extends AbstractCommand
{
    private JsonFile $bardConfig;
    private VersionInterface|Version|null $releaseVersion = null;
    private bool $isDryRun = true;

    protected function configure(): void
    {
        $this
            ->setName('release')
            ->setDescription('Create a new release')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute a dry-run with nothing being updated or released')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addArgument('release', InputArgument::REQUIRED, 'Next Release you want to start? Use format <major>.<minor>.<patch>-<PreRelease>+<BuildMetadata> or "major", "minor", "patch"')
            ->setHelp(
                <<<'EOT'
                    This command allows you to create a new release and will update the various
                    repos that have been configured. The current version can be found in the
                    `bard.json` file. This will will update the version based on the type of release
                    that you are doing.

                        <comment>%command.full_name%</comment>

                    Read more at https://docs.SonsOfPHP.com
                    EOT
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $version = $input->getArgument('release');
        if (null === $version) {
            return;
        }

        $this->bardConfig = new JsonFile($input->getOption('working-dir') . '/bard.json');
        $currentVersion   = new Version($this->bardConfig->getSection('version'));

        if (\in_array($version, ['major', 'minor', 'patch'])) {
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
            throw new RuntimeException('Cannot release a lower version');
        }

        $this->isDryRun = $input->getOption('dry-run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $formatter = $this->getHelper('formatter');
        $io        = new SymfonyStyle($input, $output);
        if ($this->isDryRun) {
            $io->note('dry-run enabled no changes will be made');
        }
        $io->text([
            sprintf('Bumping release from <info>%s</> to <info>%s</>', $this->bardConfig->getSection('version'), $this->releaseVersion->toString()),
        ]);

        // Make sure we have the latest changes
        $process = new Process(['git', 'pull', 'origin', $input->getOption('branch')]);
        $io->text($process->getCommandLine());
        if (!$this->isDryRun) {
            $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $rootComposerJsonFile = new JsonFile($input->getOption('working-dir') . '/composer.json');

        // 1. Update "replace" in main composer.json with the Package Names
        // "package/name": "self.version"
        $io->section('updating root composer.json "replace" section with package information');
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json'));
            $output->writeln([
                $formatter->formatSection($pkgComposerJsonFile->getSection('name'), 'Updating root <info>composer.json</info>'),
            ]);
            $rootComposerJsonFile = $rootComposerJsonFile->with(new UpdateReplaceSection($pkgComposerJsonFile));
        }
        $output->writeln([
            'saving root composer.json',
        ]);
        if (!$this->isDryRun) {
            file_put_contents($rootComposerJsonFile->getFilename(), $rootComposerJsonFile->toJson());
        }
        $output->writeln([
            'root composer.json file saved',
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
        $processCommands = [
            ['git', 'add', '.'],
            ['git', 'commit', '--allow-empty', '-m', sprintf('"Preparing for Release v%s"', $this->releaseVersion->toString())],
            ['git', 'push', 'origin', $input->getOption('branch')],
            ['git', 'tag', 'v' . $this->releaseVersion->toString()],
            ['git', 'push', 'origin', 'v' . $this->releaseVersion->toString()],
        ];
        foreach ($processCommands as $cmd) {
            $process = new Process($cmd);
            $io->text($process->getCommandLine());
            if (!$this->isDryRun) {
                $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
            }
        }
        $io->success('Mother Repository Released');

        // 4. Subtree Split for each package
        // @todo run split command
        $io->newLine();
        $io->title(sprintf('updating package repos with release %s', $this->releaseVersion->toString()));
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json'));
            $pkgName             = $pkgComposerJsonFile->getSection('name');
            $io->text(sprintf('Package <info>%s</> is being released', $pkgName));
            $processCommands = [
                ['git', 'subtree', 'split', '-P', $pkg['path'], '-b', $pkgName],
                ['git', 'checkout', $pkgName],
                ['git', 'push', $pkg['repository'], sprintf('%s:%s', $pkgName, $input->getOption('branch'))],
                ['git', 'tag', sprintf('%s_%s', $pkgName, $this->releaseVersion->toString())],
                ['git', 'push', $pkg['repository'], sprintf('%s_%s:v%s', $pkgName, $this->releaseVersion->toString(), $this->releaseVersion->toString())],
                ['git', 'checkout', $input->getOption('branch')],
                ['git', 'tag', '-d', sprintf('%s_%s', $pkgName, $this->releaseVersion->toString())],
                ['git', 'branch', '-D', $pkgName],
            ];
            foreach ($processCommands as $cmd) {
                $process = new Process($cmd);
                $io->text($process->getCommandLine());
                if (!$this->isDryRun) {
                    $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
                }
            }
            $io->newLine();
        }
        $io->text('All Packages have been Released');
        // $io->success('All Packages have been Released');

        // 5. Update branch alias in all composer.json files
        // - Only if update is major or minor
        // $io->section('Updating Branch Alias in root and packages');

        // 6. Update bard.json with current version
        $io->section('Updating version in bard.json');
        $this->bardConfig = $this->bardConfig->with(new UpdateVersion($this->releaseVersion));
        if (!$this->isDryRun) {
            file_put_contents($this->bardConfig->getFilename(), $this->bardConfig->toJson());
        }
        $io->text('bard.json updated with new version');

        // 7. Commit and push updates
        // $io->section('Updating mother repo');
        // $processCommands = [
        //    ['git', 'add', '.'],
        //    ['git', 'commit', '-m', '"starting release"'],
        //    ['git', 'push', 'origin', $input->getOption('branch')],
        // ];
        // foreach ($processCommands as $cmd) {
        //    $process = new Process($cmd);
        //    $io->text($process->getCommandLine());
        //    if (!$this->isDryRun) {
        //        $this->getHelper('process')->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        //    }
        // }
        // $io->text('done');

        $io->newLine();
        $io->success('Congrations on your new release');
        if ($this->isDryRun) {
            $io->note([
                'dry-run was enabled so no files were changed and no code was pushed',
            ]);
        }

        return self::SUCCESS;
    }
}
