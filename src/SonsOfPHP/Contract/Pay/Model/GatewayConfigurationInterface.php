<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay\Model;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface GatwwayConfigurationInterface
{
    public function getConfig(): array;

    public function setConfig(array $config): void;
}
