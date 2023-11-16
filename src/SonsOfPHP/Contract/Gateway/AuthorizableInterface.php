<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AuthorizableInterface
{
    public function authorize($options): void;

    public function authorizeComplete($options): void;
}
