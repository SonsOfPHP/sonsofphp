<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;

/**
 * Aggregate ID
 *
 * You can extend this class to make "UserId" class or anything else.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AggregateId implements AggregateIdInterface
{
    use AggregateIdTrait;

    /**
     * Returns a new instance with a UUID as the ID
     *
     * note: Should this go into a "AggregateUuidTrait"?
     *
     * @return static
     */
    public static function uuid()
    {
        return new static((string) Uuid::v6());
    }

    /**
     * Returns a new instance with a ULID as the ID
     *
     * note: Should this go into a "AggregateUlidTrait"?
     *
     * @return static
     */
    public static function ulid()
    {
        return new static((string) new Ulid());
    }
}
