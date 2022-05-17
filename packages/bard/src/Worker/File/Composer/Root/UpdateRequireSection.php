<?php

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * Updates the "require" section in the root composer.json file base on the
 * "require" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateRequireSection implements WorkerInterface
{
    private JsonFile $pkgComposerJsonFile;

    public function __construct(JsonFile $pkgComposerJsonFile)
    {
        $this->pkgComposerJsonFile = $pkgComposerJsonFile;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        $rootReplace = $rootComposerJsonFile->getSection('replace');
        $rootRequire = $rootComposerJsonFile->getSection('require');
        $pkgRequire = $this->pkgComposerJsonFile->getSection('require');

        if (null === $pkgRequire) {
            return $rootComposerJsonFile;
        }

        foreach ($pkgRequire as $package => $version) {
            if (in_array($package, array_keys($rootReplace))) {
                continue;
            }

            $rootRequire[$package] = $version;
        }

        return $rootComposerJsonFile->setSection('require', $rootRequire);
    }
}
