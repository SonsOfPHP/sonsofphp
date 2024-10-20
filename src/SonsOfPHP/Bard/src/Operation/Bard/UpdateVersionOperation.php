<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Bard;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;
use SonsOfPHP\Component\Version\VersionInterface;

/**
 * Updates the version in bard.json
 *
 * Example:
 *   $jsonFile->with(new UpdateVersion($version));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateVersionOperation implements OperationInterface
{
    public function __construct(private VersionInterface $version) {}

    public function apply(JsonFileInterface $bardJsonFile): JsonFileInterface
    {
        return $bardJsonFile->setSection('version', $this->version->toString());
    }
}
