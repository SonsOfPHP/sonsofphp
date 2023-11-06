<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TaggableAdapterInterface extends AdapterInterface
{
    public function clearByTag(string $tag): bool;
}
