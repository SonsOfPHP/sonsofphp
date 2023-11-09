<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Mapping;

use Attribute;

/**
 * Usage:
 *   #[AsAggregate]
 *   class UserAggregate {
 *     #[AggregateId]
 *     private $id;
 *   }
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class AggregateId
{
}
