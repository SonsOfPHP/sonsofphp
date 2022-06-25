<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Command;

use SonsOfPHP\Component\Cqrs\Command\CommandMessageInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Abstract Command Message.
 *
 * Usage:
 *   $command = new UpdateSmtpSettingsCommand([
 *      'host'     => 'smtp.example.org',
 *      'username' => 'user',
 *      'password' => 'T0pS3cr3t',
 *      'port'     => 25,
 *   ]);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractCommandMessage implements CommandMessageInterface
{
    private array $options = [];

    /**
     * Configure Options that are allowed to be passed into the command.
     *
     * @see https://symfony.com/doc/current/components/options_resolver.html
     */
    abstract protected function configureOptions(OptionsResolver $resolver): void;

    final public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Returns all the options that are defined.
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Returns a single option. If the option wasn't set, it returns null.
     *
     * @return mixed
     */
    public function getOption(string $key)
    {
        return $this->options[$key] ?? null;
    }
}
