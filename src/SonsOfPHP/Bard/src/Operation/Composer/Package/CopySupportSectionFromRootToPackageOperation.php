<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Package;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class CopySupportSectionFromRootToPackageOperation implements OperationInterface
{
    public function __construct(
        private JsonFileInterface $rootFile,
    ) {}

    public function apply(JsonFileInterface $packageFile): JsonFileInterface
    {
        $rootSupportSection = $this->rootFile->getSection('support');
        if (null === ($pkgSupportSection  = $packageFile->getSection('support'))) {
            $pkgSupportSection = [];
        }

        if (array_key_exists('docs', $rootSupportSection) && array_key_exists('docs', $pkgSupportSection)) {
            $rootSupportSection['docs'] = $pkgSupportSection['docs'];
        }

        return $packageFile->setSection('support', $rootSupportSection);
    }
}
