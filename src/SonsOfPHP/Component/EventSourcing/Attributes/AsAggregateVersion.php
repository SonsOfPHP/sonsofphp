<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Attributes;

use Attribute;

/**
 * Usage:
 *   #[AsAggregateVersion]
 *   class UserAggregateVersion {}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsAggregateVersion
{
}
