<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface LevelInterface
{
    /**
     * Returns the name of the log level
     *
     * The log name should be returned in all upercase
     *
     * This is the same value when `__toString` is called
     */
    public function getName(): string;

    /**
     * Returns true if the log levels are equal to each other
     */
    public function equals(LevelInterface $level): bool;

    public function includes(LevelInterface $level): bool;

    public function isHigherThan(LevelInterface $level): bool;

    public function isLowerThan(LevelInterface $level): bool;
}
