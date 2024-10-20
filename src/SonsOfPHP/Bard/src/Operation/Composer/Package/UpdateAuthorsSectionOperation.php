<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Package;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateAuthorsSectionOperation implements OperationInterface
{
    public function __construct(private JsonFileInterface $rootComposerJsonFile) {}

    public function apply(JsonFileInterface $pkgComposerJsonFile): JsonFileInterface
    {
        $rootSection = $this->rootComposerJsonFile->getSection('authors');

        return $pkgComposerJsonFile->setSection('authors', $rootSection);
    }
}
