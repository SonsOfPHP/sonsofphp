<?php

declare(strict_types=1);

namespace Chorale\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ConsoleStyleFactory
{
    public function create(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return new class ($input, $output) extends SymfonyStyle {
            public function __construct(
                private readonly InputInterface $input,
                private readonly OutputInterface $output,
            ) {
                parent::__construct($input, $output);
            }

            public function getInput(): InputInterface
            {
                return $this->input;
            }

            public function getOutput(): OutputInterface
            {
                return $this->output;
            }
        };
    }
}
