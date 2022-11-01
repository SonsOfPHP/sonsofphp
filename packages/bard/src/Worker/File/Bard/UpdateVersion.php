<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Bard;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;
use SonsOfPHP\Component\Version\VersionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateVersion implements WorkerInterface
{
    private VersionInterface $version;

    public function __construct(VersionInterface $version)
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $bardJsonFile): JsonFile
    {
        return $bardJsonFile->setSection('version', $this->version->toString());
    }
}
