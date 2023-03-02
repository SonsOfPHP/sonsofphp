<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Package;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Funding implements WorkerInterface
{
    private JsonFile $rootComposerJsonFile;

    public function __construct(JsonFile $rootComposerJsonFile)
    {
        $this->rootComposerJsonFile = $rootComposerJsonFile;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(JsonFile $pkgComposerJsonFile): JsonFile
    {
        $rootSection = $this->rootComposerJsonFile->getSection('funding');

        return $pkgComposerJsonFile->setSection('funding', $rootSection);
    }
}
