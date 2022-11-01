<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateIdNormalizer as BaseAggregateIdNormalizer;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @deprecated Use \SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateIdNormalizer
 */
final class AggregateIdNormalizer extends BaseAggregateIdNormalizer
{
}
