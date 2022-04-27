<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * Aggregate Version
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateVersionInterface
{
    /**
     * Create new Version
     *
     * @param int $version
     * @return AggregateVersionInterface
     */
    public static function fromInt(int $version): AggregateVersionInterface;

    /**
     * Create new Version starting at 0
     *
     * @return AggregateVersionInterface
     */
    public static function zero(): AggregateVersionInterface;

    /**
     * Returns new version object with next version number
     *
     * @return static
     */
    public function next(): AggregateVersionInterface;

    /**
     * Returns new version object with prev version number
     *
     * @return static
     */
    public function prev(): AggregateVersionInterface;

    /**
     * Compares two versions and returns true if the two versions are equal
     *
     * @param AggregateVersionInterface $that
     * @return bool
     */
    public function equals(AggregateVersionInterface $that): bool;

    /**
     * Returns the version number
     *
     * @return int
     */
    public function toInt(): int;
}
