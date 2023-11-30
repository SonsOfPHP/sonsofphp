<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Search;

use SonsOfPHP\Contract\Search\QueryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Query implements QueryInterface, \ArrayAccess, \JsonSerializable
{
    private array $fields = [
        'offset' => 0,
        'length' => null,
    ];

    /**
     * {@inheritdoc}
     */
    public function has(string $field): bool
    {
        return array_key_exists($field, $this->fields);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $field): mixed
    {
        if (!$this->has($field)) {
            return null;
        }

        return $this->fields[$field];
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $field): self
    {
        if (!in_array(strtolower($field), ['offset', 'length'])) {
            unset($this->fields[$field]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $field, mixed $value): self
    {
        if (0 === strcasecmp('offset', $field) || 0 === strcasecmp('length', $field)) {
            if (0 === strcasecmp('offset', $field) && !is_int($value)) {
                throw new \InvalidArgumentException('Offset must be integer');
            }

            if (0 === strcasecmp('length', $field) && !is_int($value) && !is_null($value)) {
                throw new \InvalidArgumentException('Length must be integer or null');
            }
            $field = strtolower($field);
        }

        $this->fields[$field] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->fields['offset'];
    }

    /**
     * {@inheritdoc}
     */
    public function getLength(): ?int
    {
        return $this->fields['length'];
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    public function jsonSerialize(): mixed
    {
        return $this->fields;
    }
}
