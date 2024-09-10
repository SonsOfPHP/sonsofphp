<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class ZoneOffset implements ZoneOffsetInterface, Stringable
{
    public function __construct(
        private int $seconds,
    ) {}

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        $lead    = '+';
        $hours   = $this->getHours();
        $minutes = (abs($this->getSeconds()) % (60 * 60)) / 60;
        if ($hours < 0) {
            $lead  = '-';
            $hours = abs($hours);
        }

        return sprintf(
            '%s%02d:%02d',
            $lead,
            $hours,
            $minutes
        );
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }

    public function getHours(): int
    {
        return (int) ($this->getSeconds() / 60 / 60);
    }
}
