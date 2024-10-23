<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle\Command;

use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugCommand extends Command
{
    public function __construct(
        private readonly FeatureToggleProviderInterface $provider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('debug:features')
            ->setDescription('Debug feature toggles')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $header = ['Key', 'Toggle'];
        $rows   = [];
        $toggle = new \ReflectionProperty(Feature::class, 'toggle');
        foreach ($this->provider->all() as $key => $feature) {
            $rows[] = [$key, $toggle->getValue($feature)::class];
        }

        $symfonyStyle->table($header, $rows);

        return Command::SUCCESS;
    }
}
