<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Enricher;

use SonsOfPHP\Contract\Logger\EnricherInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UserIdEnricher implements EnricherInterface
{
    public function __invoke(RecordInterface $record): RecordInterface
    {
        $context = $record->getContext();
        $context['user_id'] = getmyuid();

        return $record->withContext($context);
    }
}
