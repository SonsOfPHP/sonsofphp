<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ToggleableInterface
{
    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function enable(): void;

    public function disable(): void;
}
