<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Exception;

use SonsOfPHP\Contract\Filesystem\Exception\FileNotFoundExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class FileNotFoundException extends FilesystemException implements FileNotFoundExceptionInterface {}
