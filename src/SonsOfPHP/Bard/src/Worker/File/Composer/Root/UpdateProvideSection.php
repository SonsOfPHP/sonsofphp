<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateProvideSection implements WorkerInterface
{
    public function __construct(private JsonFile $pkgComposerJsonFile) {}

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
