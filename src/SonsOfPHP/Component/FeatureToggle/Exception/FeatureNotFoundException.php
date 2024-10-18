<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Exception;

use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureNotFoundExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FeatureNotFoundException extends FeatureToggleException implements FeatureNotFoundExceptionInterface {}
