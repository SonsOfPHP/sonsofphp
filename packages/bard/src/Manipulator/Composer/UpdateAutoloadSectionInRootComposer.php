<?php

namespace SonsOfPHP\Bard\Manipulator\Composer;

use SonsOfPHP\Bard\JsonFile;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateAutoloadSectionInRootComposer implements ComposerJsonFileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile
    {
        $primaryDir = pathinfo($primary->getFilename(), PATHINFO_DIRNAME);
        $pkgDir     = pathinfo($reference->getFilename(), PATHINFO_DIRNAME);
        $path       = ltrim(str_replace($primaryDir, '', $pkgDir), '/');

        $rootAutoloadSection = $primary->getSection('autoload');
        $pkgAutoloadSection  = $reference->getSection('autoload');

        foreach ($pkgAutoloadSection as $section => $config) {
            if ('psr-4' === $section) {
                foreach ($config as $namespace => $pkgPath) {
                    $rootAutoloadSection['psr-4'][$namespace] = $path.$pkgPath;
                }
            }

            if ('exclude-from-classmap' === $section) {
                foreach ($config as $pkgPath) {
                    $rootAutoloadSection['exclude-from-classmap'][] = $path.$pkgPath;
                }
            }
        }

        $rootAutoloadSection['psr-4']                 = array_unique($rootAutoloadSection['psr-4']);
        $rootAutoloadSection['exclude-from-classmap'] = array_unique($rootAutoloadSection['exclude-from-classmap']);

        return $primary->setSection('autoload', $rootAutoloadSection);
    }
}
