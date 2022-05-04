<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Year implements YearInterface
{
    private int $year;

    /**
     * @param string|int $year
     */
    public function __construct($year)
    {
        $this->year = (int) $year;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%d', $this->year);
    }

    /**
     * {@inheritdoc}
     */
    public function toInt(): int
    {
        return $this->year;
    }
}
