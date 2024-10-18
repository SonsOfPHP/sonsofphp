<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Exception;

use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureAlreadyExistsExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FeatureAlreadyExistsException extends FeatureToggleException implements FeatureAlreadyExistsExceptionInterface {}
