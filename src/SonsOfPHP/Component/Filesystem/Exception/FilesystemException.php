<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use Exception;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class FilesystemException extends Exception implements FilesystemExceptionInterface {}
