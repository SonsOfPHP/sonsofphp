<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Toggle;

use DateTimeImmutable;
use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * Date range toggle will enable the toggle if it's between two dates.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DateRangeToggle implements ToggleInterface
{
    public function __construct(
        private readonly DateTimeImmutable $start,
        private readonly DateTimeImmutable $stop,
        // @todo private ClockInterface $clock,
    ) {}

    public function isEnabled(?ContextInterface $context = null): bool
    {
        $now = new DateTimeImmutable();

        return ($this->start <= $now && $now <= $this->stop);
    }
}
