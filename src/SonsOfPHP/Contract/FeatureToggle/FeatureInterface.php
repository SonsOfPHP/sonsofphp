<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureInterface extends ToggleInterface
{
    /**
     * Returns the key that is used for the feature toggle. The key should be
     * unique enough that you can say "is_enabled('feature_toggle_key')" and
     * not get them all mixed up.
     *
     * MUST support keys consisting of the characters A-Z, a-z, 0-9, _, and .
     * in any order in UTF-8 encoding and a length of up to 64 characters
     */
    public function getKey(): string;
}
