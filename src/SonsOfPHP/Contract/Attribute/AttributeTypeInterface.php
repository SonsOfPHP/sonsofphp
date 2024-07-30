<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AttributeTypeInterface
{
    public function getDisplayName(): string;

    public function getType(): string;
}
