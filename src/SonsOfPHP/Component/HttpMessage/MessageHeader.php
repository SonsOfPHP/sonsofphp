<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
enum MessageHeader: string
{
    // @todo most of the headers should appear here, X- headers and custom headers need to be delt with as well

    case HOST       = 'host';
    case USER_AGENT = 'user-agent';

    //public function getHeaderLine(array $values): string
    //{
    //}
}
