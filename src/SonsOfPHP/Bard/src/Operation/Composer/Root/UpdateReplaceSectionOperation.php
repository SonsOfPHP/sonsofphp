<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateReplaceSectionOperation implements OperationInterface
{
    public function __construct(private JsonFile $pkgComposerJsonFile) {}

    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        /** @var array<string, string> $rootReplace */
        $rootReplace = $rootComposerJsonFile->getSection('replace');
        $pkgName     = $this->pkgComposerJsonFile->getSection('name');

        $rootReplace[$pkgName] = 'self.version';
        ksort($rootReplace);

        return $rootComposerJsonFile->setSection('replace', $rootReplace);
    }
}
