<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests;

use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class DummyCommand extends AbstractOptionsResolverCommandMessage
{
    public static $configureOptions;

    /**
     * DummyCommand::setConfigureOptionsCallback(function ($resolver) {
     *    // can now define options
     * });.
     */
    public static function setConfigureOptionsCallback($callback)
    {
        self::$configureOptions = $callback;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        if (is_callable(self::$configureOptions)) {
            call_user_func(self::$configureOptions, $resolver);
            self::$configureOptions = null;
        }
    }
}
