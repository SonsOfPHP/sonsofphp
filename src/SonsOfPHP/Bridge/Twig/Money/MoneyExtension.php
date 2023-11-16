<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Twig\Money;

use SonsOfPHP\Contract\Money\MoneyInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 */
class MoneyExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_money', [$this, 'formatMoney']),
        ];
    }

    /**
     * Formats money
     *
     * Examples
     *   MoneyInterface|format_money
     */
    public function formatMoney(MoneyInterface $money): string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $amount    = $money->getAmount()->toFloat();
        $currency  = (string) $money->getCurrency();

        return $formatter->formatCurrency($amount, $currency);
    }
}
