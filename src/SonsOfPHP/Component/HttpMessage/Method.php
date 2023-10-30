<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
enum Method: string
{
    case HEAD    = 'HEAD';
    case GET     = 'GET';
    case POST    = 'POST';
    case PUT     = 'PUT';
    case PATCH   = 'PATCH';
    case DELETE  = 'DELETE';
    case PURGE   = 'PURGE';
    case OPTIONS = 'OPTIONS';
    case TRACE   = 'TRACE';
    case CONNECT = 'CONNECT';
}
