<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate;

use ArrayObject;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Aggregate Version Normalizer.
 *
 * When using the Symfony Serializer Component, this will normalize/denormalize
 * the AggregateVersion object for the aggregate.
 *
 * When using Symfony, add this into your `services.yaml` file:
 * <code>
 * SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateVersionNormalizer:
 *     tags: [ serializer.normalizer ]
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AggregateVersionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @return int
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): ArrayObject|array|string|int|float|bool|null
    {
        return $object->toInt();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof AggregateVersionInterface;
    }

    /**
     * @return AggregateVersionInterface
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (AggregateVersionInterface::class === $type) {
            return new AggregateVersion($data);
        }

        return $type::fromInt($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_a($type, AggregateVersionInterface::class, true);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            AggregateVersionInterface::class => true,
        ];
    }
}
