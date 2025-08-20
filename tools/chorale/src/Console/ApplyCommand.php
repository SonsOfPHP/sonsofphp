<?php

declare(strict_types=1);

namespace Chorale\Console;

use Chorale\Console\Style\ConsoleStyleFactory;
use Chorale\Run\RunnerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ApplyCommand extends Command
{
    protected static $defaultName = 'apply';
    protected static $defaultDescription = 'Apply steps from a JSON plan.';

    public function __construct(
        private readonly ConsoleStyleFactory $styleFactory,
        private readonly RunnerInterface $runner,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('apply')
            ->setDescription('Apply steps from a JSON plan file.')
            ->setHelp(<<<'HELP'
Reads a plan exported from `chorale plan --json` and executes each step.

Example:
  chorale apply --project-root /path/to/repo --file plan.json
HELP)
            ->addOption('project-root', null, InputOption::VALUE_REQUIRED, 'Project root (default: CWD).')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Path to JSON plan file.', 'plan.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = $this->styleFactory->create($input, $output);
        $root = rtrim((string) ($input->getOption('project-root') ?: getcwd()), '/');
        $file = (string) $input->getOption('file');

        if (!is_file($file)) {
            $io->error('Plan file not found: ' . $file);
            return 2;
        }

        $json = json_decode((string) file_get_contents($file), true);
        if (!is_array($json) || !isset($json['steps']) || !is_array($json['steps'])) {
            $io->error('Invalid plan file.');
            return 2;
        }

        /** @var list<array<string,mixed>> $steps */
        $steps = $json['steps'];
        $this->runner->apply($root, $steps);
        $io->success(sprintf('Applied %d step(s).', count($steps)));
        return 0;
    }
}
