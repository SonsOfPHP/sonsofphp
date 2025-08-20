<?php

declare(strict_types=1);

namespace Chorale\Console;

use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Run\RunnerInterface;
use Chorale\Plan\PlanStepInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RunCommand extends Command
{
    protected static $defaultName = 'run';
    protected static $defaultDescription = 'Plan and apply steps.';

    public function __construct(
        private readonly ConsoleStyleFactory $styleFactory,
        private readonly RunnerInterface $runner,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('run')
            ->setDescription('Plan and immediately apply steps.')
            ->setHelp(<<<'HELP'
Builds a plan for the repository and applies it in a single command.
This is equivalent to running `chorale plan` followed by `chorale apply`.

Examples:
  chorale run
  chorale run --paths packages/acme --strict
HELP)
            ->addOption('project-root', null, InputOption::VALUE_REQUIRED, 'Project root (default: CWD).')
            ->addOption('paths', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Limit to specific package paths', [])
            ->addOption('force-split', null, InputOption::VALUE_NONE, 'Force split steps even if unchanged.')
            ->addOption('verify-remote', null, InputOption::VALUE_NONE, 'Verify remote state if lockfile is missing/stale.')
            ->addOption('strict', null, InputOption::VALUE_NONE, 'Fail on missing root version / unresolved conflicts / remote failures.')
            ->addOption('show-all', null, InputOption::VALUE_NONE, 'Show no-op summaries.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = $this->styleFactory->create($input, $output);
        $root   = rtrim((string) ($input->getOption('project-root') ?: getcwd()), '/');
        /** @var list<string> $paths */
        $paths  = (array) $input->getOption('paths');
        $force  = (bool) $input->getOption('force-split');
        $verify = (bool) $input->getOption('verify-remote');
        $strict = (bool) $input->getOption('strict');
        $showAll = (bool) $input->getOption('show-all');

        try {
            $result = $this->runner->run($root, [
                'paths' => $paths,
                'force_split' => $force,
                'verify_remote' => $verify,
                'strict' => $strict,
                'show_all' => $showAll,
            ]);
        } catch (\RuntimeException $e) {
            $io->error($e->getMessage());
            return 2;
        }

        $this->renderHuman($io, $result['steps'], $showAll ? ($result['noop'] ?? []) : []);
        $io->success(sprintf('Applied %d step(s).', count($result['steps'])));
        return (int) ($result['exit_code'] ?? 0);
    }

    /** @param list<PlanStepInterface> $steps */
    private function renderHuman(SymfonyStyle $io, array $steps, array $noop): void
    {
        $io->title('Chorale Run');
        $byType = [];
        foreach ($steps as $s) {
            $byType[$s->type()][] = $s;
        }

        $sections = [
            'split' => 'Split steps',
            'package-version-update' => 'Package versions',
            'package-metadata-sync'  => 'Package metadata',
            'composer-root-update'   => 'Root composer: aggregator',
            'composer-root-merge'    => 'Root composer: dependency merge',
            'composer-root-rebuild'  => 'Root composer: maintenance',
        ];

        $any = false;
        foreach ($sections as $type => $label) {
            if (empty($byType[$type])) {
                continue;
            }

            $any = true;
            $io->section($label);
            foreach ($byType[$type] as $s) {
                $a = $s->toArray();
                $io->writeln('  • ' . $this->humanLine($type, $a));
            }
        }

        if (!$any) {
            $io->writeln('No steps. Nothing to do.');
        }

        if ($noop !== []) {
            $io->newLine();
            $io->section('No-op summary (debug)');
            foreach ($noop as $group => $rows) {
                $io->writeln(sprintf('  - %s: ', $group) . count($rows));
            }
        }
    }

    /** @param array<string,mixed> $a */
    private function humanLine(string $type, array $a): string
    {
        return match ($type) {
            'split' => sprintf(
                '%s → %s [%s]%s',
                $a['path'] ?? '',
                $a['repo'] ?? '',
                $a['splitter'] ?? '',
                empty($a['reasons']) ? '' : ' {' . implode(',', (array) $a['reasons']) . '}'
            ),
            'package-version-update' => sprintf('%s — set version %s', $a['name'] ?? $a['path'] ?? '', $a['version'] ?? ''),
            'package-metadata-sync'  => sprintf(
                '%s — mirror %s',
                $a['name'] ?? $a['path'] ?? '',
                implode(',', array_keys((array) ($a['apply'] ?? [])))
            ),
            'composer-root-update'   => sprintf(
                'update %s (version %s, require %d, replace %d)',
                $a['root'] ?? '',
                $a['root_version'] ?? 'n/a',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['replace']) ? count((array) $a['replace']) : 0
            ),
            'composer-root-merge'    => sprintf(
                'require %d, require-dev %d%s',
                isset($a['require']) ? count((array) $a['require']) : 0,
                isset($a['require-dev']) ? count((array) $a['require-dev']) : 0,
                empty($a['conflicts']) ? '' : ' [conflicts: ' . count((array) $a['conflicts']) . ']'
            ),
            'composer-root-rebuild'  => sprintf('actions: %s', implode(',', (array) ($a['actions'] ?? []))),
            default => json_encode($a, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: $type,
        };
    }
}
