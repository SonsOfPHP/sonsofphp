<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ToggleInterface
{
    /**
     * Returns true if this strategy is enabled.
     *
     * Need to update this to accept an array as well
     * param ContextInterface|array|null $context
     */
    public function isEnabled(?ContextInterface $context = null): bool;
}
