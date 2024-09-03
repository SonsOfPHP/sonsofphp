<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Exception;

use Exception;
use SonsOfPHP\Contract\StateMachine\StateMachineExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class StateMachineException extends Exception implements StateMachineExceptionInterface {}
