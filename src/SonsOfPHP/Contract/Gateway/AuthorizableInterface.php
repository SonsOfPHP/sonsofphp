<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AuthorizableInterface
{
    public function authorize(array $options): void;

    public function authorizeComplete(array $options): void;
}
