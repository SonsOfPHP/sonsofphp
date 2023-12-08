<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler\Exception;

use SonsOfPHP\Contract\HttpHandler\HttpHandlerExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HttpHandlerException extends \Exception implements HttpHandlerExceptionInterface {}
