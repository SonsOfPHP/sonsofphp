<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Zone implements ZoneInterface
{
    private string $name;
    private ZoneOffsetInterface $offset;

    public function __construct(string $name, ZoneOffsetInterface $offset)
    {
        $this->name = $name;
        $this->offset = $offset;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): ZoneOffsetInterface
    {
        return $this->offset;
    }
}
