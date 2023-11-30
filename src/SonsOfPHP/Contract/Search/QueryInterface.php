<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Search;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueryInterface// extends \ArrayAccess, \JsonSerializable
{
    public function get(string $field): self;
    public function set(string $field, mixed $value): self;

    public function getOffset(): int;
    public function getLength(): ?int;
}
