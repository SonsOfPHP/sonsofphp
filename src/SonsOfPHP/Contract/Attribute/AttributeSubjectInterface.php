<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Attribute;

use Doctrine\Common\Collections\Collection;

/**
 * Classes should implement this is they want to use attributes. For example, the Product
 * class would implement this
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AttributeSubjectInterface
{
    /**
     * @return Collection<array-key, AttributeValueInterface>
     */
    public function getAttributes(): Collection;

    public function addAttribute(AttributeValueInterface $attribute): static;

    public function removeAttribute(AttributeValueInterface $attribute): static;

    public function hasAttribute(AttributeValueInterface $attribute): bool;
}
