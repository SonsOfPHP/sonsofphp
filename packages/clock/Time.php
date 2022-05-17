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
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->millisecond = $millisecond;
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
        return sprintf(
            '%02d:%02d:%02d.%03d',
            $this->getHour(),
            $this->getMinute(),
            $this->getSecond(),
            $this->getMillisecond()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecond(): int
    {
        return $this->second;
    }

    /**
     * {@inheritdoc}
     */
    public function getMillisecond(): int
    {
        return $this->millisecond;
    }
}
