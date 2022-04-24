<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Test Clock
 *
 * The test clock is used for testing purposes. It freezes time in place and has the ability
 * to update or set the time to whatever you want.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class TestClock implements ClockInterface
{
    private DateTimeZone $timezone;
    private DateTimeInterface $time;

    /**
     * @param DateTimeZone $timezone
     */
    public function __construct(?DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone ?? new DateTimeZone('UTC');
        $this->tick();
    }

    /**
     * {@inheritdoc}
     */
    public function now(): DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @return DateTimeZone
     */
    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * Updates the current clock time to be when the tick happened.
     *
     * @return void
     */
    public function tick(): void
    {
        $this->time = new DateTimeImmutable('now', $this->timezone);
    }

    /**
     * Updates the clock to a specific date and time that can be in the past or
     * in the future.
     *
     * Input should match the format: YYYY-MM-DD HH:MM:SS
     *
     * Example:
     *   $clock->tickTo('2022-04-20 04:20:00');
     *
     * @param string $input
     *
     * @return void
     *
     * @throws ClockException
     */
    public function tickTo(string $input): void
    {
        $time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $input, $this->timezone);
        if (false === $time) {
            throw new ClockException(sprintf('The input "%s" is invalid', $input));
        }

        $this->time = $time;
    }
}
