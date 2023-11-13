<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Enricher;

use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * This enricher allows you to mask data in the context.
 *
 * Examples:
 *   new MaskContextValue('credit_card_number');
 *   new MaskContextValue('cvv');
 *   new MaskContextValue(['credit_card_number', 'cvv', 'password'], '*');
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MaskContextValueEnricher implements EnricherInterface
{
    /**
     * You can add a single key or you can add an array of keys.
     *
     * If you pass in an array for the key, it will search the context keys and
     * mask the values if any are found.
     *
     * The mask value can be changed as well.
     */
    public function __construct(
        private array|string $key,
        private string $maskValue = '****',
    ) {}

    public function __invoke(RecordInterface $record): RecordInterface
    {
        $context = $record->getContext();
        if (is_string($this->key) && !isset($context[$this->key])) {
            return $record;
        }

        foreach ($this->key as $key) {
            if (isset($context[$key])) {
                $context[$key] = $this->maskValue;
            }
        }

        return $record->withContext($context);
    }
}
