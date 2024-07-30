<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Exception;

use Exception;
use SonsOfPHP\Contract\Cqrs\Exception\CqrsExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CqrsException extends Exception implements CqrsExceptionInterface {}
