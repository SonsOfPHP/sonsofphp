<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\UserAgent;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface UserAgentFactoryInterface
{
    public static function createFromGlobals(): UserAgentInterface;

    // createFromRequest(RequestInterface $request); <- PSR-7 Request
}
