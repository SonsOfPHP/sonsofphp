<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony;

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;
use Symfony\Component\Uid\Uuid;

/**
 * Aggregate ID.
 *
 * This Aggregate ID will autogenerate a UUID as the ID when none is passed in. This
 * makes it easier to use without having to use a UUID component.
 *
 * Usage:
 *   $id = new AggregateId();
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateId extends AbstractAggregateId
{
    public function __construct(?string $id = null)
    {
        if (null === $id) {
            $id = (string) Uuid::v6();
        }

        parent::__construct($id);
    }
}
