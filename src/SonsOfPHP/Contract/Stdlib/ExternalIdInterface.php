<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * An external ID is one that is used to link a local record to one that is on
 * a remote system. This is commonly used in apps that integrate with other
 * platforms. The external id is the unique id of a record in the external
 * system.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ExternalIdInterface
{
    /**
     * Returns null if the external id has not been set
     */
    public function getExternalId(): ?string;

    /**
     */
    public function setExternalId(string $externalId): void;
}
