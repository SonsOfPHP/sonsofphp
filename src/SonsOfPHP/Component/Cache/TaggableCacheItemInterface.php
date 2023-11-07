<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TaggableCacheItemInterface
{
    /**
     * Returns the tags on the cache item
     */
    public function tags(): iterable;

    /**
     * Adds tags to the cache item
     *
     * $item->tag('test');
     */
    public function tag(string|iterable $tags): static;
}
