<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Attribute;

use SonsOfPHP\Contract\Attribute\AttributeInterface;
use SonsOfPHP\Contract\Attribute\AttributeSubjectInterface;
use SonsOfPHP\Contract\Attribute\AttributeTypeInterface;
use SonsOfPHP\Contract\Attribute\AttributeValueInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AttributeValue implements AttributeValueInterface
{
    protected ?AttributeSubjectInterface $subject = null;
    protected ?AttributeInterface $attribute = null;
    protected $value = null;

    public function getSubject(): ?AttributeSubjectInterface
    {
        return $this->subject;
    }

    public function setSubject(?AttributeSubjectInterface $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getAttribute(): ?AttributeInterface
    {
        return $this->attribute;
    }

    public function setAttribute(?AttributeInterface $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getType(): ?AttributeTypeInterface
    {
        return $this->getAttribute()?->getType();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->getAttribute()?->getCode();
    }

    public function getName(): ?string
    {
        return $this->getAttribute()?->getName();
    }
}
