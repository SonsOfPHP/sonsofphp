<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface PurchasableInterface extends AuthorizableInterface, CapturableInterface
{
    public function purchase($options): void;

    public function purchaseComplete($options): void;
}
