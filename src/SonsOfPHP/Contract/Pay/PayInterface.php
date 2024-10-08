<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PayInterface
{
    public function authorize($command): void;

    public function capture($command): void;

    public function void($command): void;

    public function refund($command): void;
}
