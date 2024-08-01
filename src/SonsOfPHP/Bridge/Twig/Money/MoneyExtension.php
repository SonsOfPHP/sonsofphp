<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Twig\Money;

use SonsOfPHP\Contract\Money\MoneyFormatterInterface;
use SonsOfPHP\Contract\Money\MoneyInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 */
class MoneyExtension extends AbstractExtension
{
    public function __construct(
        private readonly MoneyFormatterInterface $formatter,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_money', $this->formatMoney(...)),
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
        return $this->formatter->format($money);
    }
}
