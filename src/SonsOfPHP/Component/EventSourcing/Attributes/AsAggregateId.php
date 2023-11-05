<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Attributes;

use Attribute;

/**
 * Usage:
 *   #[AsAggregateId]
 *   class UserAggregateId {}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsAggregateId
{
}
