<?php

namespace SonsOfPHP\Bard\Operation;

use SonsOfPHP\Component\Version\VersionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface OperationInterface
{
    /**
     * Run an operation
     *
     * $operation->run($input, $output, $version);
     */
    public function run(InputInterface $input, OutputInterface $output, ?VersionInterface $version = null): void;
}
