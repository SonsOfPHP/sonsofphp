<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\UnableToDeleteFileExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UnableToDeleteFileException extends FilesystemException implements UnableToDeleteFileExceptionInterface {}
