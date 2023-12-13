<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Session;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SessionInterface
{
    /**
     * Starts a session
     *
     * @throws SessionExceptionInterface
     */
    public function start(): bool;

    public function getName(): string;
    public function setName(string $name): void;

    public function getId(): string;
    public function setId(string $id): void;

    public function get(string $attribute, mixed $default = null): mixed;
    public function set(string $attribute, mixed $value): mixed;
    public function remove(string $attribute): mixed;
}
