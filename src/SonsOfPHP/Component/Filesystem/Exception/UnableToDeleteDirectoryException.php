<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\UnableToDeleteDirectoryExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UnableToDeleteDirectoryException extends FilesystemException implements UnableToDeleteDirectoryExceptionInterface {}
