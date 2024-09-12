<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Registry\Exception;

use SonsOfPHP\Contract\Registry\ExistingServiceExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ExistingServiceException extends \Exception implements ExistingServiceExceptionInterface {}
