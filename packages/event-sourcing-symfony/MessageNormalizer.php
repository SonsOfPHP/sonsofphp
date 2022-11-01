<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer as BaseNormalizer;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @deprecated
 */
final class MessageNormalizer extends BaseNormalizer
{
}
