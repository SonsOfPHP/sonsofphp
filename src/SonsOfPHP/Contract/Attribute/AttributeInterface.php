<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

/**
 */
interface AttributeInterface
{
    public function getCode(): ?string;

    public function setCode(?string $code): static;

    public function getName(): ?string;

    public function setName(?string $name): static;

    public function getPosition(): int;

    public function setPosition(int $position): static;

    public function getType(): ?AttributeTypeInterface;

    //public function isSystem(): bool;

    //public function isUnique(): bool;
}
