<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Message Normalizer.
 *
 * <code>
 * # services.yaml
 * services:
 *     SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer:
 *         tags: [ serializer.normalizer ]
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @return array
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'payload'  => $object->getPayload(),
            'metadata' => $object->getMetadata(),
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof MessageInterface;
    }

    /**
     * @return MessageInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $type::new()->withPayload($data['payload'])->withMetadata($data['metadata']);
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if (false === \is_array($data)) {
            return false;
        }

        if (empty($data['payload']) || empty($data['metadata'])) {
            return false;
        }

        return is_subclass_of($type, MessageInterface::class, true);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MessageInterface::class => true,
        ];
    }
}
