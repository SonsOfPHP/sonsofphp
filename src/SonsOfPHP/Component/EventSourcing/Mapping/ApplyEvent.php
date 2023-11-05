<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Mapping;

use Attribute;

/**
 * Usage:
 *   #[AsAggregate]
 *   class UserAggregate {
 *     #[ApplyEvent(Created::class)]
 *     private applyCreatedEvent(Created $event) {}
 *   }
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class ApplyEvent
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(public readonly string $event) {}
}
