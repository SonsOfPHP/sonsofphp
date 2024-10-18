<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

use SonsOfPHP\Component\FeatureToggle\Exception\InvalidArgumentException;
use SonsOfPHP\Contract\FeatureToggle\ContextInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class Feature implements FeatureInterface
{
    public function __construct(
        private string $key,
        private ToggleInterface $toggle,
    ) {
        if (1 === preg_match('/[^A-Za-z0-9_.]/', $key)) {
            throw new InvalidArgumentException(sprintf('The key "%s" is invalid. Only "A-Z", "a-z", "0-9", "_", and "." are allowed.', $key));
        }
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isEnabled(?ContextInterface $context = null): bool
    {
        return $this->toggle->isEnabled($context);
    }
}
