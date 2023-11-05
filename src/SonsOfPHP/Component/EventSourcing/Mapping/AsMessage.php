<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Mapping;

use Attribute;

/**
 * Usage:
 *   #[AsMessage]
 *   class Created {}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsMessage
{
}
