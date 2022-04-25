<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * Metadata Field Constaints
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Metadata
{
    public const EVENT_ID          = '_event_id';
    public const EVENT_TYPE        = '_event_type';
    public const AGGREGATE_ID      = '_aggregate_id';
    public const AGGREGATE_VERSION = '_aggregate_version';
    public const TIMESTAMP         = '_timestamp';
    public const TIMESTAMP_FORMAT  = '_timestamp_format';
}
