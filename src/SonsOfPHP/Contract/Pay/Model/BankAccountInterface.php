<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay\Model;

/**
 * Represents a bank account
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface BankAccountInterface
{
    public function getAccountNumber(): string;

    public function setAccountNumber(string $accountNumber): void;

    public function getRoutingNumber(): string;

    public function setRoutingNumber(string $routingNumber): void;
}
