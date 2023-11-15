<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Link;

use Psr\Link\LinkProviderInterface;
use Psr\Link\LinkInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class LinkProvider implements LinkProviderInterface
{
    protected array $links = [];

    public function __construct(
        array $links = [],
    ) {
        foreach ($links as $link) {
            if (!$link instanceof LinkInterface) {
                throw new \InvalidArgumentException('At least one link does not implement LinkInterface');
            }

            $this->links[spl_object_hash($link)] = $link;
        }
    }

    /**
     * Returns an iterable of LinkInterface objects.
     *
     * The iterable may be an array or any PHP \Traversable object. If no links
     * are available, an empty array or \Traversable MUST be returned.
     *
     * @return iterable<LinkInterface>
     */
    public function getLinks(): iterable
    {
        return array_values($this->links);
    }

    /**
     * Returns an iterable of LinkInterface objects that have a specific relationship.
     *
     * The iterable may be an array or any PHP \Traversable object. If no links
     * with that relationship are available, an empty array or \Traversable MUST be returned.
     *
     * @param string $rel
     *   The relationship name for which to retrieve links.
     *
     * @return iterable<LinkInterface>
     */
    public function getLinksByRel(string $rel): iterable
    {
        foreach ($this->links as $link) {
            if (in_array($rel, $link->getRels())) {
                yield $link;
            }
        }
    }
}
