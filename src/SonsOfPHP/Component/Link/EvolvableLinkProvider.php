<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link;

use Psr\Link\EvolvableLinkProviderInterface;
use Psr\Link\LinkInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EvolvableLinkProvider extends LinkProvider implements EvolvableLinkProviderInterface
{
    /**
     * Returns an instance with the specified link included.
     *
     * If the specified link is already present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   A link object that should be included in this collection.
     * @return static
     */
    public function withLink(LinkInterface $link): static
    {
        $that = clone $this;
        $that->links[spl_object_hash($link)] = $link;

        return $that;
    }

    /**
     * Returns an instance with the specifed link removed.
     *
     * If the specified link is not present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   The link to remove.
     * @return static
     */
    public function withoutLink(LinkInterface $link): static
    {
        $that = clone $this;
        unset($that->links[spl_object_hash($link)]);

        return $that;
    }
}
