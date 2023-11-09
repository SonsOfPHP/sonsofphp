<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ConfigurationInterface
{
    public function getDriver();
}
