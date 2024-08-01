<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Formatter;

use NumberFormatter;
use SonsOfPHP\Contract\Money\MoneyFormatterInterface;
use SonsOfPHP\Contract\Money\MoneyInterface;

/**
 * Usage:
 *   $formatter = new IntlMoneyFormatter(\NumberFormatter('en_US', \NumberFormatter::CURRENCY));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class IntlMoneyFormatter implements MoneyFormatterInterface
{
    public function __construct(
        private readonly NumberFormatter $formatter,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function format(MoneyInterface $money): string
    {
        $amount       = $money->getAmount()->toFloat();
        $currencyCode = $money->getCurrency()->getCurrencyCode();

        return $this->formatter->formatCurrency($amount, $currencyCode);
    }
}
