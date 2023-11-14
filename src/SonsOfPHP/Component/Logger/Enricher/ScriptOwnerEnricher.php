<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Enricher;

use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ScriptOwnerEnricher implements EnricherInterface
{
    public function __invoke(RecordInterface $record): RecordInterface
    {
        $context = $record->getContext();
        $context['script_owner'] = get_current_user();

        return $record->withContext($context);
    }
}
