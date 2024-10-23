<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\FeatureToggle;

use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureAlreadyExistsExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureNotFoundExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\Exception\InvalidArgumentExceptionInterface;

/**
 * Feature Toggle Provider Interface.
 *
 * The provider's resposibile is to maintain the feature toggles. These feature
 * toggles could be pulled from a database of a yaml file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FeatureToggleProviderInterface
{
    /**
     * MUST support keys consisting of the characters A-Z, a-z, 0-9, _, and .
     * in any order in UTF-8 encoding and a length of up to 64 characters
     */
    //public function getFeatureToggleByKey(string $key): ?FeatureInterface;

    /**
     * @throws InvalidArgumentExceptionInterface
     *   When the $key is invalid. MUST support keys consisting of the
     *   characters A-Z, a-z, 0-9, _, and . in any order in UTF-8 encoding and
     *   a length of up to 64 characters
     *
     * @throws FeatureNotFoundExceptionInterface
     *   Thrown when the feature cannot be found
     */
    public function get(string $key): FeatureInterface;

    /**
     * @throws InvalidArgumentExceptionInterface
     *   When the $key is invalid. MUST support keys consisting of the
     *   characters A-Z, a-z, 0-9, _, and . in any order in UTF-8 encoding and
     *   a length of up to 64 characters
     */
    public function has(string $key): bool;

    /**
     * @throws FeatureAlreadyExistsExceptionInterface
     */
    public function add(FeatureInterface $feature): void;

    /**
     * Should return iterable with the "key" being the feature's $key
     *
     * @return iterable<string, FeatureInterface>
     */
    public function all(): iterable;
}
