<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Exception;

use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureToggleExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FeatureToggleException extends \Exception implements FeatureToggleExceptionInterface {}
