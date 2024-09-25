<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Package;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class Support implements WorkerInterface
{
    public function __construct(private JsonFile $rootComposerJsonFile) {}

    public function apply(JsonFile $pkgComposerJsonFile): JsonFile
    {
        $rootSupportSection = $this->rootComposerJsonFile->getSection('support');
        $pkgSupportSection  = $pkgComposerJsonFile->getSection('support');

        // Docs may be different for package so we do not want to overwrite
        // that value
        $rootSupportSection['docs'] = $pkgSupportSection['docs'];

        return $pkgComposerJsonFile->setSection('support', $rootSupportSection);
    }
}
