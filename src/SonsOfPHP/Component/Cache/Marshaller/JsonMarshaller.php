<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Marshaller;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class JsonMarshaller implements MarshallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function marshall(mixed $value): string
    {
        return json_encode($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unmarshall(string $value): mixed
    {
        return json_decode($value);
    }
}
