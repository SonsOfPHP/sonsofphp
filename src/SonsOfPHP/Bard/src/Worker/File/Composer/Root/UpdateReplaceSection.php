<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateReplaceSection implements WorkerInterface
{
    public function __construct(private readonly JsonFile $pkgComposerJsonFile) {}

    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        $rootReplace = $rootComposerJsonFile->getSection('replace');
        $pkgName     = $this->pkgComposerJsonFile->getSection('name');

        $rootReplace[$pkgName] = 'self.version';

        return $rootComposerJsonFile->setSection('replace', $rootReplace);
    }
}
