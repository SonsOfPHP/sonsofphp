<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Package;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateBranchAliasSectionOperation implements OperationInterface
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
