<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Aggregate Version Normalizer.
 *
 * When using the Symfony Serializer Component, this will normalize/denormalize
 * the AggregateVersion object for the aggregate.
 *
 * When using Symfony, add this into your `services.yaml` file:
 * <code>
 * SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateVersionNormalizer:
 *     tags: [ serializer.normalizer ]
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateVersionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, string $format = null, array $context = [])
    {
        return $object->toInt();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AggregateVersionInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (AggregateVersionInterface::class === $type) {
            return new AggregateVersion($data);
        }

        return $type::fromInt($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, AggregateVersionInterface::class, true);
    }
}
