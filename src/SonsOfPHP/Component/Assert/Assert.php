<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert;

use BadMethodCallException;
use Exception;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Assert
{
    public const INVALID_ARRAY     = 1;
    public const INVALID_BOOLEAN   = 2;
    public const INVALID_CALLABLE  = 3;
    public const INVALID_COUNTABLE = 4;
    public const INVALID_DOUBLE    = 5;
    public const INVALID_FLOAT     = 6;
    public const INVALID_INTEGER   = 7;
    public const INVALID_ITERABLE  = 8;
    public const INVALID_NULL      = 9;
    public const INVALID_NUMERIC   = 10;
    public const INVALID_OBJECT    = 11;
    public const INVALID_RESOURCE  = 12;
    public const INVALID_SCALAR    = 13;
    public const INVALID_STRING    = 14;

    protected static string $exceptionClass = InvalidArgumentException::class;
    protected static bool $throwException = true;

    public static function getExceptionClass(): string
    {
        return static::$exceptionClass;
    }

    public static function setExceptionClass(string $class): void
    {
        static::$exceptionClass = $class;
    }

    public static function disable(): void
    {
        static::$throwException = false;
    }

    public static function enable(): void
    {
        static::$throwException = true;
    }

    public static function string($value, ?string $message = null): bool
    {
        if (!is_string($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "string" got "%s"', $value), self::INVALID_STRING);
            return false;
        }

        return true;
    }

    public static function integer($value, ?string $message = null): bool
    {
        if (!is_int($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "int" got "%s"', $value), self::INVALID_INTEGER);
            return false;
        }

        return true;
    }

    public static function float($value, ?string $message = null): bool
    {
        if (!is_float($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "float" got "%s"', $value), self::INVALID_FLOAT);
            return false;
        }

        return true;
    }

    public static function numeric($value, ?string $message = null): bool
    {
        if (!is_numeric($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "numeric" got "%s"', $value), self::INVALID_NUMERIC);
            return false;
        }

        return true;
    }

    public static function boolean($value, ?string $message = null): bool
    {
        if (!is_bool($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "boolean" got "%s"', $value), self::INVALID_BOOLEAN);
            return false;
        }

        return true;
    }

    public static function scalar($value, ?string $message = null): bool
    {
        if (!is_scalar($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "scalar" got "%s"', $value), self::INVALID_SCALAR);
            return false;
        }

        return true;
    }

    public static function object($value, ?string $message = null): bool
    {
        if (!is_object($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "object" got "%s"', $value), self::INVALID_OBJECT);
            return false;
        }

        return true;
    }

    public static function callable($value, ?string $message = null): bool
    {
        if (!is_callable($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "callable" got "%s"', $value), self::INVALID_CALLABLE);
            return false;
        }

        return true;
    }

    public static function array($value, ?string $message = null): bool
    {
        if (!is_array($value)) {
            static::throwException(static::generateMessage($message ?? 'Expected "array" got "%s"', $value), self::INVALID_ARRAY);
            return false;
        }

        return true;
    }

    public static function empty($value, ?string $message = null): bool
    {
        if (!empty($value)) {
            static::throwException($message);
            return false;
        }

        return true;
    }

    public static function null($value, ?string $message = null): bool
    {
        if (null !== $value) {
            static::throwException(static::generateMessage($message ?? 'Expected "null" got "%s"', $value), self::INVALID_BOOLEAN);
            return false;
        }

        return true;
    }

    public static function true($value, ?string $message = null): bool
    {
        if (true !== $value) {
            static::throwException(static::generateMessage($message ?? 'Expected "true" got "%s"', $value), self::INVALID_BOOLEAN);
            return false;
        }

        return true;
    }

    public static function false($value, ?string $message = null): bool
    {
        if (false !== $value) {
            static::throwException(static::generateMessage($message ?? 'Expected "false" got "%s"', $value), self::INVALID_BOOLEAN);
            return false;
        }

        return true;
    }

    public static function __callStatic(string $method, array $args)
    {
        if (str_starts_with($method, 'nullOr')) {
            if (null !== $args[0]) {
                $method = lcfirst(substr($method, 6));
                call_user_func_array([static::class, $method], $args);
            }

            return true;
        }

        if (str_starts_with($method, 'all')) {
            $method = lcfirst(substr($method, 3));
            $values = array_shift($args);
            foreach ($values as $value) {
                call_user_func_array([static::class, $method], array_merge([$value], $args));
            }

            return true;
        }

        if (str_starts_with($method, 'not')) {
            $method = lcfirst(substr($method, 3));

            try {
                $ret = call_user_func_array([static::class, $method], $args);
            } catch (InvalidArgumentException) {
                return true;
            }

            if (true === $ret) {
                static::throwException(static::generateMessage('not "%s" got "%s"', $method, ...$args));
            }

            return false;
        }

        throw new BadMethodCallException(sprintf('Unknown method "%s"', $method));
    }

    protected static function createException(?string $message = null, int $code = 0): Exception
    {
        $class = static::$exceptionClass;

        return new $class($message ?? '', $code);
    }

    protected static function throwException(?string $message = null, int $code = 0): void
    {
        if (static::$throwException) {
            throw static::createException($message ?? '', $code);
        }
    }

    protected static function valueToString(mixed $value): string
    {
        $debugType = get_debug_type($value);
        return match($debugType) {
            'bool' => $value ? 'true' : 'false',
            'DateTimeImmutable',
            'DateTime' => sprintf('%s: %s', $value::class, $value->format('c')),
            default => $debugType,
        };
    }

    protected static function generateMessage(string $message, ...$values): string
    {
        foreach ($values as $key => $value) {
            $values[$key] = static::valueToString($value);
        }

        return sprintf($message, ...$values);
    }

    private function __construct() {}
    private function __clone() {}
}
