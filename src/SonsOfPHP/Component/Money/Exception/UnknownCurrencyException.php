<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Exception;

use Exception;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UnknownCurrencyException extends Exception implements MoneyExceptionInterface {}
