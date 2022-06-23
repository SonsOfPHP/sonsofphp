<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Message Normalizer.
 *
 * <code>
 * # services.yaml
 * SonsOfPHP\Bridge\Symfony\EventSourcing\MessageNormalizer:
 *     tags: [ serializer.normalizer ]
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessageNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'payload'  => $object->getPayload(),
            'metedata' => $object->getMetadata(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AggregateMessageInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $type::new()->withPayload($data['payload'])->withMetadata($data['metadata']);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, MessageInterface::class, true);
    }
}
