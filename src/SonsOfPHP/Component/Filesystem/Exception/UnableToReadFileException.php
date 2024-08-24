<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\UnableToReadFileExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UnableToReadFileException extends FilesystemException implements UnableToReadFileExceptionInterface {}
