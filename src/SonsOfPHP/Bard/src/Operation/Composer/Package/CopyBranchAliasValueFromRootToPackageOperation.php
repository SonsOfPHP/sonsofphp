<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Package;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class CopyBranchAliasValueFromRootToPackageOperation implements OperationInterface
{
    public function __construct(
        private JsonFileInterface $rootFile,
    ) {}

    public function apply(JsonFileInterface $packageFile): JsonFileInterface
    {
        $rootExtraSection = $this->rootFile->getSection('extra');
        $pkgExtraSection  = $packageFile->getSection('extra');

        $pkgExtraSection['branch-alias'] = $rootExtraSection['branch-alias'];

        return $packageFile->setSection('extra', $pkgExtraSection);
    }
}
