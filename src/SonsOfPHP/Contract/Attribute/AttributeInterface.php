<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AttributeInterface
{
    public function getCode(): ?string;

    /**
     * The code is unique, no two attributes should have the same code. The code
     * should ONLY consist of letters, numbers, underscores, and periods.
     *
     * Example Code: sku
     *
     * @throws InvalidArgumentException
     *   - When the code is invalid or contains invalid characters
     */
    public function setCode(?string $code): static;

    public function getName(): ?string;

    /**
     * The name of the attribute. This could be displayed on the frontend to
     * users or used anywhere. This is the friendly version of the code. This
     * does not have to be unique.
     *
     * @throws InvalidArgumentException
     *   - When the name is invalid for any reason
     */
    public function setName(?string $name): static;

    public function getType(): ?AttributeTypeInterface;

    /**
     * The attribute type such as Text, Textarea, Select, etc.
     */
    public function setType(AttributeTypeInterface $type): static;

    public function getPosition(): int;

    /**
     * The position helps with ordering. When returning the list of attributes, the position
     * should determine what order they are listed in.
     */
    public function setPosition(int $position): static;

    /**
     * Is this a system attribute? System attributes should not be editable by
     * a user.
     */
    //public function isSystem(): bool;

    /**
     * If the attribute is unique, the values should be checked. An example of
     * a unique value would be an email address. This will help avoid duplicate
     * resources.
     */
    //public function isUnique(): bool;

    /**
     * Similar to a system attribute, this means that the name and code should
     * not be modified by users
     */
    //public function isLocked(): bool;
}
