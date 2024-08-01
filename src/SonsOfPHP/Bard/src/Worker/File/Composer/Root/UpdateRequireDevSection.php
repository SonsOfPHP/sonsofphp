<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * Updates the "require-dev" section in the primary composer.json file base on the
 * "require-dev" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateRequireDevSection implements WorkerInterface
{
    public function __construct(private JsonFile $pkgComposerJsonFile) {}

    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        $rootRequireDev = $rootComposerJsonFile->getSection('require-dev');
        $pkgRequireDev  = $this->pkgComposerJsonFile->getSection('require-dev');
        $rootRequire    = $rootComposerJsonFile->getSection('require');
        $rootReplace    = $rootComposerJsonFile->getSection('replace');


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

        return $rootComposerJsonFile->setSection('require-dev', $rootRequireDev);
    }
}
