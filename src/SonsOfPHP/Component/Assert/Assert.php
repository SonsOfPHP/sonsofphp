<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Assert;

use Throwable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Assert
{
    protected static string $exceptionClass = InvalidArgumentException::class;
    protected static bool $throwException = true;

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
        if (!\is_string($value)) {
            static::throwException($message);
            return false;
        }

        return true;
    }

    protected static function throwException(?string $message = null, int $code = 0, ?Throwable $previous = null): void
    {
        $class = static::$exceptionClass;

        if (static::$throwException) {
            throw new $class($message ?? '', $code, $previous);
        }
    }

    private function __construct() {}
    private function __clone() {}
}
