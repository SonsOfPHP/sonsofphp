<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Mapping\AsAggregate;
use Psr\Container\ContainerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateManager implements AggregateManagerInterface
{
    private array $aggregates = [];

    public function __construct(
        private ConfigurationInterface $config,
    ) {}

    public function registerAggregate(string $aggregate): void
    {
        foreach ($this->config->getDriver()->getClassAttributes($aggregate) as $attribute) {
            if ($attribute instanceof AsAggregate) {
                $this->aggregates[$aggregate] = new AggregateClassMetadata($aggregate);
                return;
            }
        }

        throw new \Exception('Invalid Aggregate');
    }

    public function find(AggregateInterface $class, AggregateIdInterface|string $id): ?AggregateInterface
    {
        if (!array_key_exists($class, $this->aggregates)) {
            $this->registerAggregate($aggregate);
        }

        return $this->config->getContainer()->get($this->aggregate[$class]->getAggregateRepositoryClass())->find($id);
    }

    public function persist(AggregateInterface $aggregate): void
    {
        if (!array_key_exists($class, $this->aggregates)) {
            $this->registerAggregate($aggregate);
        }

        $events = $aggregate->getPendingEvents();
        if (0 === count($events)) {
            return;
        }

        $this->config->getContainer()->get($this->aggregate[$class]->getMessageRepositoryClass())->persist($aggregate);
    }
}
