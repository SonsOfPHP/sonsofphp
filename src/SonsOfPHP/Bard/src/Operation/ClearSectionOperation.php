<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation;

use SonsOfPHP\Bard\JsonFileInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class ClearSectionOperation implements OperationInterface
{
    public function __construct(private string $section) {}

    public function apply(JsonFileInterface $rootComposerJsonFile): JsonFileInterface
    {
        return $rootComposerJsonFile->setSection($this->section, []);
    }
}
