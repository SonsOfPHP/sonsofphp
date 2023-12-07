<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\AddressInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Address implements AddressInterface, \Stringable
{
    public function __construct(private string $email, private ?string $name = null) {}

    public function __toString(): string
    {
        if (null === $this->name) {
            return $this->email;
        }

        return sprintf('%s <%s>', $this->name, $this->email);
    }

    /**
     * {@inheritdoc}
     */
    public static function from(string $address): self
    {
        return new self($address);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function withEmail(string $email): static
    {
        if ($email === $this->email) {
            return $this;
        }

        $that = clone $this;
        $that->email = $email;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function withName(?string $name): static
    {
        if ($name === $this->name) {
            return $this;
        }

        $that = clone $this;
        $that->name = $name;

        return $that;
    }
}
