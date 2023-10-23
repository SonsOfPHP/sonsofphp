<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\UserAgent;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface UserAgentProviderInterface
{
    public function getForUA(string $ua): UserAgentInterface;
}
