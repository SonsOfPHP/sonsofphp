<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * Feature Interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureInterface
{
    /**
     * Returns the key that is used for the feature toggle. The key should be
     * unique enough that you can say "is_granted('feature_toggle_key')" and not
     * get them all mixed up.
     */
    public function getKey(): string;

    /**
     * Returns true or false if the toggle is enabled.
     */
    public function isEnabled(ContextInterface $context): bool;
}
