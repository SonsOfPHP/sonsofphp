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
        private readonly array|string $key,
        private readonly string $maskValue = '****',
    ) {}

    public function __invoke(RecordInterface $record): RecordInterface
    {
        $context = $record->getContext();

        $keys = $this->key;

        if (!is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            if (isset($context[$key])) {
                $context[$key] = $this->maskValue;
            }
        }

        return $record->withContext($context);
    }
}
