<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Pay\Marshaller;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MarshallerInterface
{
    public function marshall(mixed $value): string;

    public function unmarshall(string $value): mixed;
}
