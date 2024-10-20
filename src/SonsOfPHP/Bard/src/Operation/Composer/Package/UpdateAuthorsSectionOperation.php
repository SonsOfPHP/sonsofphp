<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Package;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateAuthorsSectionOperation implements OperationInterface
{
    public function __construct(private JsonFile $rootComposerJsonFile) {}

    public function apply(JsonFile $pkgComposerJsonFile): JsonFile
    {
        $rootSection = $this->rootComposerJsonFile->getSection('authors');

        return $pkgComposerJsonFile->setSection('authors', $rootSection);
    }
}
