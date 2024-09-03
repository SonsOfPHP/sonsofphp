<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Exception;

use SonsOfPHP\Contract\StateMachine\UnsupportedSubjectExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UnsupportedSubjectException extends StateMachineException implements UnsupportedSubjectExceptionInterface {}
