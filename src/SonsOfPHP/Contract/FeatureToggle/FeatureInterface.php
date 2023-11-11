<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * Feature Interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureInterface extends ToggleInterface
{
    /**
     * Returns the key that is used for the feature toggle. The key should be
     * unique enough that you can say "is_granted('feature_toggle_key')" and not
     * get them all mixed up.
     */
    public function getKey(): string;
}
