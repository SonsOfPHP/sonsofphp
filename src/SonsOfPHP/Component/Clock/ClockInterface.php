<?php

declare(strict_types=1);
/**
 */

namespace SonsOfPHP\Component\Clock;

use DateTimeInterface;

/**
 */
interface ClockInterface
{
    /**
     */
    public function now(): DateTimeInterface;
}
