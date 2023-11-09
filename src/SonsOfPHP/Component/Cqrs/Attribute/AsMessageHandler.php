<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Attribute;

use Attribute;

/**
 * Usage
 *   #[AsMessageHandler(CreateUser::class)]
 *   final class CreateUserHandler {}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class AsMessageHandler
{
    public function __construct(
        public readonly string $messageClass,
    ) {}
}
