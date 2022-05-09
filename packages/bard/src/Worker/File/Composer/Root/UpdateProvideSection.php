<?php

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateProvideSection implements WorkerInterface
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
        $pkgProvideSection = $this->pkgComposerJsonFile->getSection('provide');
        if (null === $pkgProvideSection) {
            return $rootComposerJsonFile;
        }

        $rootProvideSection = $rootComposerJsonFile->getSection('provide');

        foreach ($pkgProvideSection as $pkg => $version) {
            $rootProvideSection[$pkg] = $version;
        }

        return $rootComposerJsonFile->setSection('provide', $rootProvideSection);
    }
}
