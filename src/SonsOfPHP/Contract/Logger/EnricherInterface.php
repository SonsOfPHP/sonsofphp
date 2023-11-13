<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EnricherInterface
{
    public function __invoke($record);
}
