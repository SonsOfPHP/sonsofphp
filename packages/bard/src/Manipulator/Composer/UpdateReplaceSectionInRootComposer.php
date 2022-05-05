<?php

namespace SonsOfPHP\Bard\Manipulator\Composer;

use SonsOfPHP\Bard\JsonFile;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateReplaceSectionInRootComposer implements ComposerJsonFileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile
    {
        $rootReplace = $primary->getSection('replace');
        $pkgName     = $reference->getSection('name');

        $rootReplace[$pkgName] = 'self.version';

        return $primary->setSection('replace', $rootReplace);
    }
}
