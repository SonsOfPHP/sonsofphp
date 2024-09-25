<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class ClearSection implements WorkerInterface
{
    public function __construct(private string $section) {}

    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        return $rootComposerJsonFile->setSection($this->section, []);
    }
}
