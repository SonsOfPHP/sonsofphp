<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Vault\Marshaller;

use JsonException;
use SonsOfPHP\Component\Vault\Exception\MarshallingException;

/**
 * Serializes values using JSON.
 */
final class JsonMarshaller implements MarshallerInterface
{
    public function marshall(mixed $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new MarshallingException('Unable to encode value.', 0, $jsonException);
        }
    }

    public function unmarshall(string $value): mixed
    {
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new MarshallingException('Unable to decode value.', 0, $jsonException);
        }
    }
}
