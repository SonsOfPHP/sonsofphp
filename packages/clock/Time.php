<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Time implements TimeInterface
{
    private int $hour;
    private int $minute;
    private int $second;
    private int $millisecond;

    public function __construct(int $hour, int $minute, int $second, int $millisecond)
    {
        $this->hour        = $hour;
        $this->minute      = $minute;
        $this->second      = $second;
        $this->millisecond = $millisecond;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecond(): int
    {
        return $this->second;
    }

    /**
     * {@inheritDoc}
     */
    public function getMillisecond(): int
    {
        return $this->millisecond;
    }
}
