<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Bard;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;
use SonsOfPHP\Component\Version\VersionInterface;

/**
 * Updates the version in bard.json
 *
 * Example:
 *   $jsonFile->with(new UpdateVersion($version));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateVersionWorker implements WorkerInterface
{
    public function __construct(private VersionInterface $version) {}

    public function apply(JsonFile $bardJsonFile): JsonFile
    {
        return $bardJsonFile->setSection('version', $this->version->toString());
    }
}
