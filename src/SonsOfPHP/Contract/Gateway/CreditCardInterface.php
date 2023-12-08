<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CreditCardInterface
{
    /**
     * name on card
     */
    public function getCardHolder(): string;

    public function setCardHolder(string $cardHolder): self;

    public function getCardNumber(): string;

    public function setCardNumber(string $cardNumber): self;

    /**
     * Returns month in format MM
     */
    public function getExpirationMonth(): string;

    /**
     * Returns year in format YY
     */
    public function getExpirationYear(): string;

    /**
     * Month and Year SHOULD be in MM and YY format, however this should
     * also be able to take a full year (2020) or just a single month (4)
     */
    public function setExpiration(string|int $month, string|int $year): self;

    /**
     * Returns the 4DBC, CVC, CVV code for the credit card
     */
    public function getSecurityCode(): string;

    public function setSecurityCode(string $securityCode): self;
}
