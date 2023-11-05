<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Mapping\Driver;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AttributeDriver implements DriverInterface
{
    public function getReflectionClass($class)
    {
        return new \ReflectionClass($class);
    }

    public function getClassAttributes($class): iterable
    {
        foreach ($this->getReflectionClass($class)->getAttributes() as $refAttribute) {
            // @todo only return supported attributes
            yield $refAttribute->newInstance();
        }
    }

    public function getMethodAttributes($class): iterable
    {
        foreach ($this->getReflectionClass($class)->getMethods() as $refMethod) {
            foreach ($refMethod->getAttributes() as $attribute) {
                // @todo only return supported attributes
                yield $refMethod->getName() => $attribute->newInstance();
            }
        }
    }

    public function getPropertyAttributes($class): iterable
    {
        foreach ($this->getReflectionClass($class)->getProperties() as $refProperty) {
            foreach ($refProperty->getAttributes() as $attribute) {
                // @todo only return supported attributes
                yield $refProperty->getName() => $attribute->newInstance();
            }
        }
    }

    public function getPropertyAttribute($class, $property)
    {
        foreach ($this->getPropertyAttributes($class) as $i => $attribute) {
            if ($i === $property) {
                return $attribute;
            }
        }

        return null;
    }
}
