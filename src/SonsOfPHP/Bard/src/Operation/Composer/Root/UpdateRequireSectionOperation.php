<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Root;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * Updates the "require" section in the root composer.json file base on the
 * "require" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateRequireSectionOperation implements OperationInterface
{
    public function __construct(private JsonFileInterface $pkgComposerJsonFile) {}

    public function apply(JsonFileInterface $rootComposerJsonFile): JsonFileInterface
    {
        /** @var array<string, string> $rootReplace */
        $rootReplace = $rootComposerJsonFile->getSection('replace');

        /** @var array<string, string> $rootRequire */
        $rootRequire = $rootComposerJsonFile->getSection('require');

        /** @var array<string, string> $pkgRequire */
        $pkgRequire = $this->pkgComposerJsonFile->getSection('require');

        if (null === $pkgRequire) {
            return $rootComposerJsonFile;
        }

        foreach ($pkgRequire as $package => $version) {
            if (array_key_exists($package, $rootReplace)) {
                unset($rootRequire[$package]);
                continue;
            }

            $rootRequire[$package] = $version;
        }

        foreach (array_keys($rootRequire) as $package) {
            if (array_key_exists($package, $rootReplace)) {
                unset($rootRequire[$package]);
            }
        }

        ksort($rootRequire);

        return $rootComposerJsonFile->setSection('require', $rootRequire);
    }
}
