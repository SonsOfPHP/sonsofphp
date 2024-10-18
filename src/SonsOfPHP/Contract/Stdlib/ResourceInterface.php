<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * Resource is something that is usually stored in the database
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ResourceInterface
{
    /**
     * Unique Identifier
     *
     * This could return a string, int, UUID object, or null if the ID has not
     * been set yet
     */
    public function getId(): mixed;
}
