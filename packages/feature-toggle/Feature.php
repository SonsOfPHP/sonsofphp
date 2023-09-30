<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * Feature.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Feature implements FeatureInterface
{
    private string $key;
    private ToggleInterface $toggle;

    public function __construct(string $key, ToggleInterface $toggle)
    {
        $this->key    = $key;
        $this->toggle = $toggle;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isEnabled(ContextInterface $context): bool
    {
        return $this->toggle->isEnabled($context);
    }
}
