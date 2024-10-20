<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Bard;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class AddPackageOperation implements OperationInterface
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
                    $bardConfig->getRealPath(),
                ));
            }
        }

        $packages[] = $this->config;

        return $bardConfig->setSection('packages', $packages);
    }
}
