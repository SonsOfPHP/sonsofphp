<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie\Exception;

use Exception;
use SonsOfPHP\Contract\Cookie\CookieExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CookieException extends Exception implements CookieExceptionInterface {}
