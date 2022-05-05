<?php

namespace SonsOfPHP\Bard\Manipulator\Composer;

use SonsOfPHP\Bard\JsonFile;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateProvideSectionInRootComposer implements ComposerJsonFileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile
    {
        $pkgProvideSection = $reference->getSection('provide');
        if (null === $pkgProvideSection) {
            return $primary;
        }

        $rootProvideSection = $primary->getSection('provide');

        foreach ($pkgProvideSection as $pkg => $version) {
            $rootProvideSection[$pkg] = $version;
        }

        return $primary->setSection('provide', $rootProvideSection);
    }
}
