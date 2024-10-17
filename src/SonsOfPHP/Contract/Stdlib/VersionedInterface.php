<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface VersionedInterface
{
    public function getVersion(): int|string|null;

    public function setVersion(int|string $version): void;
}
