<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Stdlib;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TimestampableInterface
{
    /**
     * Should always return a timestamp
     */
    public function getCreatedAt(): \DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): void;

    /**
     * Should always return a timestamp
     */
    public function getUpdatedAt(): \DateTimeInterface;

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void;
}
