<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateVersionNormalizer as BaseNormalizer;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @deprecated Use \SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateVersionNormalizer
 */
final class AggregateVersionNormalizer extends BaseNormalizer
{
}
