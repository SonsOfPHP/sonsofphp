<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Enricher;

use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * The handler that says, "fuck your message"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NullEnricher implements EnricherInterface
{
    public function __invoke(RecordInterface $record): RecordInterface
    {
        return $record;
    }
}
