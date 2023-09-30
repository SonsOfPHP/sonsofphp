<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate;

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
     * {@inheritDoc}
     *
     * @return string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return (string) $object;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AggregateIdInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @return AggregateIdInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (AggregateIdInterface::class === $type) {
            return new AggregateId($data);
        }

        return $type::fromString($data);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, AggregateIdInterface::class, true);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            AggregateIdInterface::class => true,
        ];
    }
}
