<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * Log Enrichers will enrich the log record with additional context and data.
 * Enrichers will modify the Record.
 *
 * Examples:
 *   - Add Http Request information to the Context
 *   - Mask Context values, for example, if someone accendentally added
 *     "creditCardNumber" to the context, it will "****" the value out.
 *     - Can be used for PCI / Hippa Compliance
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EnricherInterface
{
    public function __invoke(RecordInterface $record): RecordInterface;
}
