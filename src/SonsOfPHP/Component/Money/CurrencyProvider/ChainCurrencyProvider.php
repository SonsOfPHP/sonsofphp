<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\CurrencyProvider;

use SonsOfPHP\Contract\Money\CurrencyProviderInterface;

/**
 * Chain Currency Provider.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ChainCurrencyProvider extends AbstractCurrencyProvider
{
    private array $providers = [];

    public function __construct(?array $providers = [])
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function addProvider(CurrencyProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function getCurrencies(): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getCurrencies();
        }
    }
}
