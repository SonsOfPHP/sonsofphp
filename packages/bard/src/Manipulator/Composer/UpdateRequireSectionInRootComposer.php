<?php

namespace SonsOfPHP\Bard\Manipulator\Composer;

use SonsOfPHP\Bard\JsonFile;

/**
 * Updates the "require" section in the primary composer.json file base on the
 * "require" section in the the reference composer.json file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateRequireSectionInRootComposer implements ComposerJsonFileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile
    {
        $rootReplace = $primary->getSection('replace');
        $rootRequire = $primary->getSection('require');
        $pkgRequire  = $reference->getSection('require');

        if (!$pkgRequire) {
            return $primary;
        }

        foreach ($pkgRequire as $package => $version) {
            if (in_array($package, array_keys($rootReplace))) {
                continue;
            }

            $rootRequire[$package] = $version;
        }

        return $primary->setSection('require', $rootRequire);
    }
}
