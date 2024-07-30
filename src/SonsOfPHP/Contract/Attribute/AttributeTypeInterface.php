<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

interface AttributeTypeInterface
{
    public function getDisplayName(): string;

    public function getType(): string;
}
