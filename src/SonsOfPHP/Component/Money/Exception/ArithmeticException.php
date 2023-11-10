<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Exception;

use SonsOfPHP\Contract\Money\Exception\ArithmeticExceptionInterface;

/**
 * Arithmetic Exception.
 *
 * This is thrown when the math is fucked up like dividing by zero
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ArithmeticException extends \Exception implements ArithmeticExceptionInterface {}
