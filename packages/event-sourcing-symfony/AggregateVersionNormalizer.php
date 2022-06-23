<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Aggregate Version Normalizer.
 *
 * When using the Symfony Serializer Component, this will normalize/denormalize
 * the AggregateVersion object for the aggregate.
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
        return $type::fromInt($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, AggregateVersionInterface::class, true);
    }
}
