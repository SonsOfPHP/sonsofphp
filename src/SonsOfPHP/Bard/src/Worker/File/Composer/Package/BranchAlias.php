<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Package;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class BranchAlias implements WorkerInterface
{
    public function __construct(private JsonFile $rootComposerJsonFile) {}

    public function apply(JsonFile $pkgComposerJsonFile): JsonFile
    {
        $rootExtraSection = $this->rootComposerJsonFile->getSection('extra');
        $pkgExtraSection  = $pkgComposerJsonFile->getSection('extra');

        $pkgExtraSection['branch-alias'] = $rootExtraSection['branch-alias'];

        return $pkgComposerJsonFile->setSection('extra', $pkgExtraSection);
    }
}
