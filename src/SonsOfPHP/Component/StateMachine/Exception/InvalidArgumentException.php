<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Exception;

use SonsOfPHP\Contract\StateMachine\StateMachineExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements StateMachineExceptionInterface {}
