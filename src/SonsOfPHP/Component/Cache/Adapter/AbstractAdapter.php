<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SonsOfPHP\Component\Cache\Marshaller\MarshallerInterface;
use SonsOfPHP\Component\Cache\Marshaller\SerializableMarshaller;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAdapter implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected array $deferred = [];

    public function __construct(
        protected int $defaultTTL = 0,
        protected ?MarshallerInterface $marshaller = null,
    ) {
        if (!$this->marshaller instanceof MarshallerInterface) {
            $this->marshaller = new SerializableMarshaller();
        }
    }

    public function __destruct()
    {
        $this->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = []): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->getItem($key);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        $isOk = true;
        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $this->logger?->debug(sprintf('Unable to delete key "%s".', $key));
                $isOk = false;
            }
        }

        return $isOk;
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        $isOk = true;

        foreach ($this->deferred as $item) {
            if (!$this->save($item)) {
                $this->logger?->debug(sprintf('Unable to save key "%s".', $item->getKey()));
                $isOk = false;
            }
        }

        $this->deferred = [];

        return $isOk;
    }
}
