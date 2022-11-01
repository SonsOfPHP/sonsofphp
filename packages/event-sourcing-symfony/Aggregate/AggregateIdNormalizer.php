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
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return (string) $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AggregateIdInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (AggregateIdInterface::class === $type) {
            return new AggregateId($data);
        }

        return $type::fromString($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, AggregateIdInterface::class, true);
    }
}
