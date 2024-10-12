<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * Code is used as a unique identifier. This is not the same as an ID, but
 * could be used as part of a unique constrait in a database. For example, you
 * could use an Account ID with a code to ensure only a single record exists
 * for that account. An example of this could be a Product UPC.
 *
 * NOTE: The code could be auto-generated or user generated.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CodeAwareInterface
{
    /**
     * Returns the code that has been set on the object. If the code has not
     * been set, this MUST return null
     */
    public function getCode(): ?string;

    /**
     * The code MUST be a string and this interface does not limit the code to
     * length, characters, or anything else. That is up to the implementation
     * library.
     *
     * @throws \InvalidArgumentException If the $code is invalid
     */
    public function setCode(string $code): void;
}
