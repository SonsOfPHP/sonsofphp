<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Bard;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class AddPackageWorker implements WorkerInterface
{
    public function __construct(
        private array $config,
    ) {}

    public function apply(JsonFileInterface $bardConfig): JsonFileInterface
    {
        if (null === $packages = $bardConfig->getSection('packages')) {
            $packages = [];
        }

        foreach ($packages as $pkg) {
            if ($pkg['path'] === $this->config['path']) {
                // @todo PackageAlreadyExistsException
                throw new \Exception(sprintf(
                    'Package already exists at path "%s" in "%s"',
                    $pkg['path'],
                    $bardConfig->getFilename(),
                ));
            }
        }

        $packages[] = $this->config;

        return $bardConfig->setSection('packages', $packages);
    }
}
