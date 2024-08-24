<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\UnableToCopyFileExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UnableToCopyFileException extends FilesystemException implements UnableToCopyFileExceptionInterface {}
