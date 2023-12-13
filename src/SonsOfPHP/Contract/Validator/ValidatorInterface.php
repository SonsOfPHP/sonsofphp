<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Validator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ValidatorInterface
{
    public function validate(mixed $object, array $groups = []): iterable;
    public function validateProperty(mixed $object, string $propertyName, array $groups = []): iterable;
    public function validateValue(mixed $object, string $propertyName, object $value, array $groups = []): iterable;

    public function getConstrainsForClass(string $class);
}
