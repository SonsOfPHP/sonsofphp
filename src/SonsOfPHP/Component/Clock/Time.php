<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Time implements TimeInterface
{
    public function __construct(
        private int $hour,
        private int $minute,
        private int $second,
        private int $millisecond
    ) {}

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return sprintf(
            '%02d:%02d:%02d.%03d',
            $this->getHour(),
            $this->getMinute(),
            $this->getSecond(),
            $this->getMillisecond()
        );
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getSecond(): int
    {
        return $this->second;
    }

    public function getMillisecond(): int
    {
        return $this->millisecond;
    }
}
