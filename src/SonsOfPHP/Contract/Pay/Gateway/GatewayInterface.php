<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay\Gateway;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface GatewayInterface
{
    public function command(CommandInterface $command): void;

    public function query(QueryInterface $command): mixed;
}
