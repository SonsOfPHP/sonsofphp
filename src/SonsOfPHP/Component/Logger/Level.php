<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LogLevel;
use SonsOfPHP\Contract\Logger\LevelInterface;
use UnhandledMatchError;
use ValueError;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
enum Level: int implements LevelInterface
{
    case Emergency = 0; // Highest
    case Alert     = 1;
    case Critical  = 2;
    case Error     = 3;
    case Warning   = 4;
    case Notice    = 5;
    case Info      = 6;
    case Debug     = 7; // Lowest

    public static function fromName(string $name): LevelInterface
    {
        try {
            return match (strtolower($name)) {
                'emergency' => self::Emergency,
                'alert'     => self::Alert,
                'critical'  => self::Critical,
                'error'     => self::Error,
                'warning'   => self::Warning,
                'notice'    => self::Notice,
                'info'      => self::Info,
                'debug'     => self::Debug,
            };
        } catch (UnhandledMatchError $e) {
            throw new ValueError(sprintf('"%s" is invalid', $name), $e->getCode(), $e);
        }
    }

    public static function tryFromName(string $name): ?LevelInterface
    {
        try {
            return self::fromName($name);
        } catch (ValueError) {
        }

        return null;
    }

    public function getName(): string
    {
        return match($this) {
            self::Emergency => 'EMERGENCY',
            self::Alert     => 'ALERT',
            self::Critical  => 'CRITICAL',
            self::Error     => 'ERROR',
            self::Warning   => 'WARNING',
            self::Notice    => 'NOTICE',
            self::Info      => 'INFO',
            self::Debug     => 'DEBUG',
        };
    }

    public function equals(LevelInterface $level): bool
    {
        return $this->value === $level->value;
    }

    public function includes(LevelInterface $level): bool
    {
        return $this->value >= $level->value;
    }

    public function isHigherThan(LevelInterface $level): bool
    {
        return $this->value < $level->value;
    }

    public function isLowerThan(LevelInterface $level): bool
    {
        return $this->value > $level->value;
    }

    public function toPsrLogLevel(): string
    {
        return match($this) {
            self::Emergency => LogLevel::EMERGENCY,
            self::Alert     => LogLevel::ALERT,
            self::Critical  => LogLevel::CRITICAL,
            self::Error     => LogLevel::ERROR,
            self::Warning   => LogLevel::WARNING,
            self::Notice    => LogLevel::NOTICE,
            self::Info      => LogLevel::INFO,
            self::Debug     => LogLevel::DEBUG,
        };
    }
}
