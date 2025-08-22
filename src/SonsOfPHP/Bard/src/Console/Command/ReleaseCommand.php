<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Console\Command;

use RuntimeException;
use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\Bard\UpdateVersionOperation;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyBranchAliasValueFromRootToPackageOperation;
use SonsOfPHP\Component\Version\Version;
use SonsOfPHP\Component\Version\VersionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Release command will take in user input and call the other release commands.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReleaseCommand extends AbstractCommand
{
    private ?VersionInterface $currentVersion = null;

    private ?VersionInterface $releaseVersion = null;

    private bool $isDryRun = true;

    private JsonFile $rootComposerJsonFile;

    protected function configure(): void
    {
        $this
            ->setName('release')
            ->setDescription('Create a new release')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute a dry-run with nothing being updated or released')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'What branch we working with?', 'main')
            ->addArgument('release', InputArgument::REQUIRED, 'Next Release you want to start? Use format <major>.<minor>.<patch>-<PreRelease>+<BuildMetadata> or "major", "minor", "patch"')
            ->setHelp(
                <<<'HELP'
This command allows you to create a new release and will update the various
repos that have been configured. The current version can be found in the
`bard.json` file. This will will update the version based on the type of release
that you are doing.

    <comment>%command.full_name%</comment>

Read more at https://docs.SonsOfPHP.com
HELP
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $version = $input->getArgument('release');
        if (null === $version) {
            return;
        }

        // Last version that was released
        $this->currentVersion = new Version($this->bardConfig->getSection('version'));
        if (\in_array($version, ['major', 'minor', 'patch'])) {
            switch ($version) {
                case 'major':
                    $this->releaseVersion = $this->currentVersion->nextMajor();
                    break;
                case 'minor':
                    $this->releaseVersion = $this->currentVersion->nextMinor();
                    break;
                case 'patch':
                    $this->releaseVersion = $this->currentVersion->nextPatch();
                    break;
            }
        } else {
            $this->releaseVersion = new Version($version);
        }

        if ($this->currentVersion->isGreaterThan($this->releaseVersion)) {
            throw new RuntimeException('Cannot release a lower version');
        }

        $this->isDryRun = $input->getOption('dry-run');

        $this->rootComposerJsonFile = new JsonFile($input->getOption('working-dir') . '/composer.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bardStyle->title(sprintf(
            'Releasing "%s"',
            $this->releaseVersion->toString()
        ));

        if ($this->isDryRun) {
            $this->bardStyle->note('dry-run enabled no changes will be made');
            if (!$this->bardStyle->confirm('[dry-run] Continue?', true)) {
                return self::SUCCESS;
            }
        }

        // Make sure we are on the correct branch
        $this->bardStyle->text(sprintf(
            'Making sure we are on branch "%s"...',
            $input->getOption('branch'),
        ));
        if (!$this->isDryRun) {
            $process = new Process(['git', 'checkout', $input->getOption('branch')]);
            $this->getProcessHelper()->mustRun(
                $output,
                $process,
                sprintf(
                    'There was and error running command: %s',
                    $process->getCommandLine()
                )
            );
        }

        $this->bardStyle->text('...OK');
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 0. Make sure we have the latest changes
        $this->pullLatestChanges($input, $output);
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 1. Update "replace" in main composer.json with the Package Names
        // "package/name": "self.version"
        $this->bardStyle->section('Merging composer.json files');
        $cmdInput = new ArrayInput([
            'command'   => 'merge',
            '--dry-run' => $this->isDryRun,
        ]);
        $this->getApplication()->doRun($cmdInput, $output);
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // @todo
        // 2. Update Changelog
        // Changes the "Unreleased" headline to the version we are releaseing
        // Adds new headline at top for unreleased features

        // 3. Tag Release and push
        $this->tagReleaseAndPushMonorepo($input, $output);
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 4. Subtree Split for each package
        $this->tagReleaseAndPushPackages($input, $output);
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 5. Update branch alias in all composer.json files
        $this->updateBranchAliasForPackages($input);
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 6. Update bard.json with current version
        $this->updateBardConfigVersion();
        if ($this->isDryRun && !$this->bardStyle->confirm('[dry-run] Continue to next step?', true)) {
            return self::SUCCESS;
        }

        // 7. Commit and push updates
        $this->commitAndPushNewChanges($input, $output);

        $this->bardStyle->success(sprintf(
            'Version "%s" has been released',
            $this->releaseVersion->toString(),
        ));

        if ($this->isDryRun) {
            $this->bardStyle->note([
                'dry-run was enabled so no files were changed and no code was pushed',
            ]);
        }

        return self::SUCCESS;
    }

    private function pullLatestChanges(InputInterface $input, OutputInterface $output): void
    {
        $this->bardStyle->text(sprintf(
            'Pulling latest changes from "%s"...',
            $input->getOption('branch'),
        ));

        if (!$this->isDryRun) {
            $process = new Process(['git', 'pull', 'origin', $input->getOption('branch')]);
            if ($output->isDebug()) {
                $this->bardStyle->text($process->getCommandLine());
            }

            $this->getProcessHelper()->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
        }

        $this->bardStyle->text('...OK');
    }

    private function tagReleaseAndPushMonorepo(InputInterface $input, OutputInterface $output): void
    {
        $this->bardStyle->section(sprintf(
            'Releasing Monorepo "%s"',
            $this->releaseVersion->toString()
        ));

        $processCommands = [
            ['git', 'add', '.'],
            ['git', 'commit', '--allow-empty', '-m', sprintf('"Preparing for Release v%s"', $this->releaseVersion->toString())],
            ['git', 'push', 'origin', $input->getOption('branch')],
            ['git', 'tag', 'v' . $this->releaseVersion->toString()],
            ['git', 'push', 'origin', 'v' . $this->releaseVersion->toString()],
        ];
        foreach ($processCommands as $cmd) {
            $process = new Process($cmd);
            $this->bardStyle->text($process->getCommandLine());
            if (!$this->isDryRun) {
                $this->getProcessHelper()->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
            }
        }

        $this->bardStyle->success('Monorepo released');
    }

    private function tagReleaseAndPushPackages(InputInterface $input, OutputInterface $output): void
    {
        $this->bardStyle->section(sprintf(
            'Releasing packages "%s"',
            $this->releaseVersion->toString()
        ));

        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json'));
            $pkgName             = $pkgComposerJsonFile->getSection('name');
            $this->bardStyle->text($this->getFormatterHelper()->formatSection($pkgName, 'Releasing...'));
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
                $this->bardStyle->text($process->getCommandLine());
                if (!$this->isDryRun) {
                    $this->getProcessHelper()->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
                }
            }

            $this->bardStyle->text($this->getFormatterHelper()->formatSection($pkgName, '...Done'));
            $this->bardStyle->newLine();
        }

        $this->bardStyle->success('All Packages have been Released');
    }

    /**
     * Foreach package, this will update the extra.branch-alias to the same
     * value as the root composer.json value
     */
    private function updateBranchAliasForPackages(InputInterface $input): void
    {
        $this->bardStyle->section('Updating the Branch Alias for root and packages');

        // Update the branch alias for root composer.json
        $extra = $this->rootComposerJsonFile->getSection('extra');
        $branchAlias = explode('.', $this->releaseVersion->toString());
        $branchAlias[2] = 'x-dev';
        $branchAlias = implode('.', $branchAlias);
        $extra['branch-alias'] = $branchAlias;
        $this->rootComposerJsonFile->setSection('extra', $extra);
        $this->bardStyle->text('root composer.json updated to ' . $branchAlias);
        if (!$this->isDryRun) {
            $this->rootComposerJsonFile->save();
        }

        // Update all packages branch alias based on the root composer.json
        foreach ($this->bardConfig->getSection('packages') as $pkg) {
            $pkgComposerJsonFile = new JsonFile(realpath($input->getOption('working-dir') . '/' . $pkg['path'] . '/composer.json'));
            $pkgComposerJsonFile = $pkgComposerJsonFile->with(new CopyBranchAliasValueFromRootToPackageOperation($this->rootComposerJsonFile));
            $this->bardStyle->text($this->getFormatterHelper()->formatSection($pkgComposerJsonFile->getSection('name'), 'Updated branch alias to "' . $branchAlias . '"'));
            if (!$this->isDryRun) {
                $pkgComposerJsonFile->save();
            }
        }

        $this->bardStyle->success('Done');
    }

    private function updateBardConfigVersion(): void
    {
        $this->bardStyle->text(sprintf(
            'Updating version to "%s" in bard config...',
            $this->releaseVersion->toString()
        ));
        $this->bardConfig = $this->bardConfig->with(new UpdateVersionOperation($this->releaseVersion));
        if (!$this->isDryRun) {
            $this->bardConfig->save();
        }

        $this->bardStyle->text('...OK');
    }

    private function commitAndPushNewChanges(InputInterface $input, OutputInterface $output): void
    {
        $this->bardStyle->section('Commiting and new Pushing Changes');
        $processCommands = [
            ['git', 'add', '.'],
            ['git', 'commit', '-m', '"starting next release"'],
            ['git', 'push', 'origin', $input->getOption('branch')],
        ];
        foreach ($processCommands as $cmd) {
            $process = new Process($cmd);
            $this->bardStyle->text($process->getCommandLine());
            if (!$this->isDryRun) {
                $this->getProcessHelper()->mustRun($output, $process, sprintf('There was and error running command: %s', $process->getCommandLine()));
            }
        }

        $cmdInput = new ArrayInput([
            'command'     => 'push',
            '--dry-run'   => $this->isDryRun,
            '--branch'    => $input->getOption('branch'),
        ]);
        $this->getApplication()->doRun($cmdInput, $output);
    }
}
