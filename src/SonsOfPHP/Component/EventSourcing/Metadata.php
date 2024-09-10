<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * Metadata Field Constaints.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Metadata
{
    public const EVENT_ID          = '__event_id';

    public const EVENT_TYPE        = '__event_type';

    public const AGGREGATE_ID      = '__aggregate_id';

    public const AGGREGATE_VERSION = '__aggregate_version';

    public const TIMESTAMP         = '__timestamp';

    public const TIMESTAMP_FORMAT  = '__timestamp_format';

    // @see https://www.php.net/manual/en/datetime.format.php
    public const DEFAULT_TIMESTAMP_FORMAT = 'c';
}
