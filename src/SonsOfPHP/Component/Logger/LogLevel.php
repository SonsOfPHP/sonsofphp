<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use Psr\Log\LogLevel as BaseLogLevel;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
enum LogLevel: string
{
    case EMERGENCY = 'emergency';
    case ALERT     = 'alert';
    case CRITICAL  = 'critical';
    case ERROR     = 'error';
    case WARNING   = 'warning';
    case NOTICE    = 'notice';
    case INFO      = 'info';
    case DEBUG     = 'debug';
}
