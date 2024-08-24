<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\UnableToWriteFileExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UnableToWriteFileException extends FilesystemException implements UnableToWriteFileExceptionInterface {}
