<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

use InvalidArgumentException;
use Stringable;

/**
 * Represents and Email Address such as "Joshua Estes <joshua@sonsofphp.com>"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AddressInterface extends Stringable
{
    /**
     * @throws InvalidArgumentException
     *   If the $address is invalid, for example if the string does not contain
     *   an email address
     */
    public static function from(string $address): self;

    /**
     * This MUST return the email address ONLY
     */
    public function getEmail(): string;

    /**
     * Returns a new instance of the class if $email is different, if $email is
     * the same, this MUST return the same instance.
     *
     * @throws InvalidArgumentException
     */
    public function withEmail(string $email): static;

    /**
     * Just will return null if no name was set
     *
     * If a name was set, this will return just the name, ie "Joshua Estes"
     */
    public function getName(): ?string;

    /**
     * @throws InvalidArgumentException
     */
    public function withName(?string $name): static;
}
