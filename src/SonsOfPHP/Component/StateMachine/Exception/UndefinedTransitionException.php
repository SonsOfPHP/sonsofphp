<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Exception;

use SonsOfPHP\Contract\StateMachine\UndefinedTransitionExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UndefinedTransitionException extends StateMachineException implements UndefinedTransitionExceptionInterface {}
