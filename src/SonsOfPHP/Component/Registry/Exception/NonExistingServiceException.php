<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Registry\Exception;

use SonsOfPHP\Contract\Registry\NonExistingServiceExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NonExistingServiceException extends \Exception implements NonExistingServiceExceptionInterface {}
