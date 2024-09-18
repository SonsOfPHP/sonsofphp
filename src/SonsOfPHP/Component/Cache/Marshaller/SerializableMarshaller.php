<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Marshaller;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class SerializableMarshaller implements MarshallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function marshall(mixed $value): string
    {
        return serialize($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unmarshall(string $value): mixed
    {
        return unserialize($value);
    }
}
