<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Provider;

use SonsOfPHP\Component\FeatureToggle\Exception\FeatureAlreadyExistsException;
use SonsOfPHP\Component\FeatureToggle\Exception\FeatureNotFoundException;
use SonsOfPHP\Component\FeatureToggle\Exception\InvalidArgumentException;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemoryFeatureToggleProvider implements FeatureToggleProviderInterface
{
    public $feature;

    private array $features = [];

    /**
     * @param FeatureInterface[] $features
     */
    public function __construct(array $features = [])
    {
        foreach ($features as $feature) {
            $this->add($feature);
        }
    }

    public function get(string $key): FeatureInterface
    {
        if ($this->has($key)) {
            return $this->feature[$key];
        }

        throw new FeatureNotFoundException(sprintf('Feature "%s" not found', $key));
    }

    public function has(string $key): bool
    {
        if (1 === preg_match('/[^A-Za-z0-9_.]/', $key)) {
            throw new InvalidArgumentException(sprintf('The key "%s" is invalid. Only "A-Z", "a-z", "0-9", "_", and "." are allowed.', $key));
        }

        return array_key_exists($key, $this->features);
    }

    public function add(FeatureInterface $feature): void
    {
        if ($this->has($feature->getKey())) {
            throw new FeatureAlreadyExistsException(sprintf('Feature "%s" already exists', $feature->getKey()));
        }

        $this->features[$feature->getKey()] = $feature;
    }

    public function all(): iterable
    {
        foreach ($this->features as $key => $feature) {
            yield $key => $feature;
        }
    }
}
