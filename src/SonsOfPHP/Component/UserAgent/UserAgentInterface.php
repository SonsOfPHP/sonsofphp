<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\UserAgent;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface UserAgentInterface extends \Stringable, \JsonSerializable
{
    public function isBot(): bool;

    public function isMobile(): bool;

    public function isTablet(): bool;

    public function isDesktop(): bool;
}
