<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate;

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
     * {@inheritdoc}
     *
     * @return int
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return $object->toInt();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AggregateVersionInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @return AggregateVersionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (AggregateVersionInterface::class === $type) {
            return new AggregateVersion($data);
        }

        return $type::fromInt($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, AggregateVersionInterface::class, true);
    }
}
