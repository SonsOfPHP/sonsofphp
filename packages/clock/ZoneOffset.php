<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ZoneOffset implements ZoneOffsetInterface
{
    private int $seconds;

    public function __construct(int $seconds)
    {
        $this->seconds = $seconds;
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
        $lead = '+';
        $hours = $this->getHours();
        $minutes = (abs($this->getSeconds()) % (60 * 60)) / 60;
        if ($hours < 0) {
            $lead = '-';
            $hours = abs($hours);
        }

        return sprintf(
            '%s%02d:%02d',
            $lead,
            $hours,
            $minutes
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    public function getHours(): int
    {
        return (int) ($this->getSeconds() / 60 / 60);
    }
}
