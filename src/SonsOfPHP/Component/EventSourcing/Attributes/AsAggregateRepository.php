<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Attributes;

use Attribute;

/**
 * Usage:
 *   #[AsAggregateRepository]
 *   class UserAggregateRepository {}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsAggregateRepository
{
}
