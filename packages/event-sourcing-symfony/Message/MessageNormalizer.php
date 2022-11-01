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
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'payload' => $object->getPayload(),
            'metadata' => $object->getMetadata(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof MessageInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $type::new()->withPayload($data['payload'])->withMetadata($data['metadata']);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_subclass_of($type, MessageInterface::class, true);
    }
}
