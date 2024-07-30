<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AttributeValueInterface
{
    public function getSubject(): ?AttributeSubjectInterface;

    public function setSubject(?AttributeSubjectInterface $subject): static;

    public function getAttribute(): ?AttributeInterface;

    public function setAttribute(?AttributeInterface $attribute): static;

    public function getValue();

    public function setValue($value): static;

    /**
     * Returns the code of the Attribute
     */
    public function getCode(): ?string;

    /**
     * Returns the name of the Attribute
     */
    public function getName(): ?string;

    /**
     * Returns the type of Attribute
     */
    public function getType(): ?AttributeTypeInterface;
}
