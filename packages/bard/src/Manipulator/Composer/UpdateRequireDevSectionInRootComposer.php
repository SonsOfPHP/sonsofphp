<?php

namespace SonsOfPHP\Bard\Manipulator\Composer;

use SonsOfPHP\Bard\JsonFile;

/**
 * Updates the "require-dev" section in the primary composer.json file base on the
 * "require-dev" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateRequireDevSectionInRootComposer implements ComposerJsonFileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile
    {
        $rootRequireDev = $primary->getSection('require-dev');
        $pkgRequireDev  = $reference->getSection('require-dev');

        if (!$pkgRequireDev) {
            return $primary;
        }

        foreach ($pkgRequireDev as $package => $version) {
            $rootRequireDev[$package] = $version;
        }

        return $primary->setSection('require-dev', $rootRequireDev);
    }
}
