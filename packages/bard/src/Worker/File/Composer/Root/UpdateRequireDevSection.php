<?php

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * Updates the "require-dev" section in the primary composer.json file base on the
 * "require-dev" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateRequireDevSection implements WorkerInterface
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
        $rootRequireDev = $rootComposerJsonFile->getSection('require-dev');
        $pkgRequireDev  = $this->pkgComposerJsonFile->getSection('require-dev');

        if (null === $pkgRequireDev) {
            return $rootComposerJsonFile;
        }

        foreach ($pkgRequireDev as $package => $version) {
            $rootRequireDev[$package] = $version;
        }

        return $rootComposerJsonFile->setSection('require-dev', $rootRequireDev);
    }
}
