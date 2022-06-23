<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Aggregate ID Normalizer.
 *
 * When using the Symfony Serializer Component, this will normalize/denormalize
 * the AggregateId object for the aggregate.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateIdNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, string $format = null, array $context = [])
    {
        return (string) $object;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AggregateIdInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $type::fromString($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, AggregateIdInterface::class, true);
    }
}
