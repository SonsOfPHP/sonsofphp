<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Exception;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ClientException extends \Exception implements ClientExceptionInterface {}
