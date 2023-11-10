<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Money\Exception;

/**
 * Arithmetic Exception.
 *
 * This is thrown when the math is fucked up like dividing by zero
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ArithmeticExceptionInterface extends MoneyExceptionInterface {}
