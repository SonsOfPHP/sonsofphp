<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate;

use ArrayObject;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Aggregate ID Normalizer.
 *
 * When using the Symfony Serializer Component, this will normalize/denormalize
 * the AggregateId object for the aggregate.
 *
 * When using Symfony, add this into your `services.yaml` file:
 * <code>
 * SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateIdNormalizer:
 *     tags: [ serializer.normalizer ]
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AggregateIdNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @return string
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): ArrayObject|array|string|int|float|bool|null
    {
        return (string) $object;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof AggregateIdInterface;
    }

    /**
     * @return AggregateIdInterface
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (AggregateIdInterface::class === $type) {
            return new AggregateId($data);
        }

        return $type::fromString($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_a($type, AggregateIdInterface::class, true);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            AggregateIdInterface::class => true,
        ];
    }
}
