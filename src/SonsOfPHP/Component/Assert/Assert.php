<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert;

use BadMethodCallException;
use Exception;
use Stringable;

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
    protected static bool $throwException   = true;

    public static function getExceptionClass(): string
    {
        return static::$exceptionClass;
    }

    public static function setExceptionClass(string $class): void
    {
        static::$exceptionClass = $class;
    }

    /**
     * Disable throwing exceptions
     */
    public static function disable(): void
    {
        static::$throwException = false;
    }

    /**
     * Enable throwing exceptions
     */
    public static function enable(): void
    {
        static::$throwException = true;
    }

    public static function string(mixed $value, ?string $message = null): bool
    {
        if (is_string($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "string" got "%s"', $value),
            self::INVALID_STRING
        );
    }

    public static function int(mixed $value, ?string $message = null): bool
    {
        if (is_int($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "int" got "%s"', $value),
            self::INVALID_INTEGER
        );
    }

    public static function float(mixed $value, ?string $message = null): bool
    {
        if (is_float($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "float" got "%s"', $value),
            self::INVALID_FLOAT
        );
    }

    public static function numeric(mixed $value, ?string $message = null): bool
    {
        if (is_numeric($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "numeric" got "%s"', $value),
            self::INVALID_NUMERIC
        );
    }

    public static function bool(mixed $value, ?string $message = null): bool
    {
        if (is_bool($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "boolean" got "%s"', $value),
            self::INVALID_BOOLEAN
        );
    }

    public static function scalar(mixed $value, ?string $message = null): bool
    {
        if (is_scalar($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "scalar" got "%s"', $value),
            self::INVALID_SCALAR
        );
    }

    public static function object(mixed $value, ?string $message = null): bool
    {
        if (is_object($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "object" got "%s"', $value),
            self::INVALID_OBJECT
        );
    }

    public static function callable(mixed $value, ?string $message = null): bool
    {
        if (is_callable($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "callable" got "%s"', $value),
            self::INVALID_CALLABLE
        );
    }

    public static function array(mixed $value, ?string $message = null): bool
    {
        if (is_array($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "array" got "%s"', $value),
            self::INVALID_ARRAY
        );
    }

    public static function resource(mixed $value, ?string $message = null): bool
    {
        if (is_resource($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "resource" got "%s"', $value),
            self::INVALID_ARRAY
        );
    }

    public static function empty(mixed $value, ?string $message = null): bool
    {
        if (empty($value)) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "empty" got "%s"', $value)
        );
    }

    public static function null(mixed $value, ?string $message = null): bool
    {
        if (null === $value) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "null" got "%s"', $value),
            self::INVALID_NULL
        );
    }

    public static function true(mixed $value, ?string $message = null): bool
    {
        if (true === $value) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "true" got "%s"', $value),
            self::INVALID_BOOLEAN
        );
    }

    public static function false(mixed $value, ?string $message = null): bool
    {
        if (false === $value) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "false" got "%s"', $value),
            self::INVALID_BOOLEAN
        );
    }

    public static function eq(mixed $value, mixed $value2, ?string $message = null): bool
    {
        if ($value == $value2) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "%s" == "%s"', $value, $value2)
        );
    }

    public static function same(mixed $value, mixed $value2, ?string $message = null): bool
    {
        if ($value === $value2) {
            return true;
        }

        return static::throwException(
            static::generateMessage($message ?? 'Expected "%s" == "%s"', $value, $value2)
        );
    }

    protected static function createException(?string $message = null, int $code = 0): Exception
    {
        $class = static::$exceptionClass;

        return new $class($message ?? '', $code);
    }

    protected static function throwException(?string $message = null, int $code = 0): false
    {
        if (static::$throwException) {
            throw static::createException($message ?? '', $code);
        }

        return false;
    }

    protected static function valueToString(mixed $value): string
    {
        $type      = gettype($value);
        $debugType = get_debug_type($value);

        return match ($type) {
            'boolean'  => $value ? 'true' : 'false',
            'integer'  => sprintf('%d', $value),
            'double'   => sprintf('%f', $value),
            'string'   => $value,
            'NULL'     => 'null',
            'resource' => $debugType,
            'object'   => match ($debugType) {
                'DateTimeImmutable',
                'DateTime' => sprintf('%s: %s', $value::class, $value->format('c')),
                default    => $value instanceof Stringable ? (string) $value : $debugType,
            },
            default => $type,
        };
    }

    protected static function generateMessage(string $message, ...$values): string
    {
        foreach ($values as $key => $value) {
            $values[$key] = static::valueToString($value);
        }

        return sprintf($message, ...$values);
    }

    /**
     * Usage:
     *   nullOr{method}(mixed $value, string $message)
     *   all{method}(array $values, string $message)
     *   not{method}(mixed $value, string $message)
     */
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

            return static::throwException(
                static::generateMessage('not "%s" got "%s"', $method, ...$args)
            );
        }

        throw new BadMethodCallException(sprintf('Unknown method "%s"', $method));
    }

    /** @codeCoverageIgnore */
    private function __construct() {}

    /** @codeCoverageIgnore */
    private function __clone() {}
}
