<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateClassMetadataInterface
{
    // aggregate class name
    public function getName(): string;

    // property name for aggregate id
    public function getAggregateId(): string;

    // property name for aggregate version
    public function getAggregateVersion(): string;

    public function getReflectinClass(): \ReflectionClass;

    // If any upserters, return them
    //public function getUpserters(): iterable;

    // returns the serializer to use
    //public function getSerializer(): MessageSerializerInterface;

    //public function getAggregateRepository(): AggregateRepositoryInterface;

    //public function getMessageRepository(): MessageRepositoryInterface;

    //public function getMessageProviderInterface(): MessageProviderInterface;
}
