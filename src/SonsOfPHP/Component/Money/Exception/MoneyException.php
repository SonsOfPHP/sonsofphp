<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Exception;

use Exception;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * Money Exception.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MoneyException extends Exception implements MoneyExceptionInterface {}
