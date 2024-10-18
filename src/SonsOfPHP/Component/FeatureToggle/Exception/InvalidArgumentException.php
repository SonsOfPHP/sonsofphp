<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Exception;

use SonsOfPHP\Contract\FeatureToggle\Exception\InvalidArgumentExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements InvalidArgumentExceptionInterface {}
