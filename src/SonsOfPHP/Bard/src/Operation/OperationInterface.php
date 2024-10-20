<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation;

use SonsOfPHP\Bard\JsonFileInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface OperationInterface
{
    public function apply(JsonFileInterface $jsonFile);
}
