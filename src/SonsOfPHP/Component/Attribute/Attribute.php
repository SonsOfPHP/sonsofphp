<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Attribute;

use SonsOfPHP\Contract\Attribute\AttributeInterface;
use SonsOfPHP\Contract\Attribute\AttributeTypeInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Attribute implements AttributeInterface, \Stringable
{
    protected ?string $code = null;
    protected ?string $name = null;
    protected int $position = 0;
    protected ?AttributeTypeInterface $type = null;

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        // Normalize value
        $this->code = strtolower((string) $code);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getType(): ?AttributeTypeInterface
    {
        return $this->type;
    }

    public function setType(AttributeTypeInterface $type): static
    {
        $this->type = $type;

        return $this;
    }
}
