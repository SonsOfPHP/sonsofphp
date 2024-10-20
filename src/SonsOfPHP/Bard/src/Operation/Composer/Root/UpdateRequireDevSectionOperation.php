<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Root;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * Updates the "require-dev" section in the primary composer.json file base on the
 * "require-dev" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateRequireDevSectionOperation implements OperationInterface
{
    public function __construct(private JsonFileInterface $pkgComposerJsonFile) {}

    public function apply(JsonFileInterface $rootComposerJsonFile): JsonFileInterface
    {
        /** @var array<string, string> $rootRequireDev */
        $rootRequireDev = $rootComposerJsonFile->getSection('require-dev');

        /** @var array<string, string> $pkgRequireDev */
        $pkgRequireDev = $this->pkgComposerJsonFile->getSection('require-dev');

        /** @var array<string, string> $rootRequire */
        $rootRequire = $rootComposerJsonFile->getSection('require');

        /** @var array<string, string> $rootReplace */
        $rootReplace = $rootComposerJsonFile->getSection('replace');


        if (null === $pkgRequireDev) {
            return $rootComposerJsonFile;
        }

        foreach ($pkgRequireDev as $package => $version) {
            if (array_key_exists($package, $rootRequire)) {
                if (array_key_exists($package, $rootRequireDev)) {
                    unset($rootRequireDev[$package]);
                }

                continue;
            }

            if (array_key_exists($package, $rootReplace)) {
                if (array_key_exists($package, $rootRequireDev)) {
                    unset($rootRequireDev[$package]);
                }

                if (array_key_exists($package, $rootRequire)) {
                    unset($rootRequire[$package]);
                }

                continue;
            }

            $rootRequireDev[$package] = $version;
        }

        foreach (array_keys($rootRequireDev) as $package) {
            if (array_key_exists($package, $rootReplace)) {
                unset($rootRequireDev[$package]);
            }
        }

        ksort($rootRequireDev);

        return $rootComposerJsonFile->setSection('require-dev', $rootRequireDev);
    }
}
