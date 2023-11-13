<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

/**
 * "Record" is a log record, this is what is used by Handlers, Formatters, and Enrichers
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface RecordInterface
{
    public function getChannel(): string;

    public function withChannel(): static;

    public function getLevel();

    public function withLevel(): static;

    public function getMessage(): string;

    public function withMessage(): static;

    public function getContext(): ContextInterface;

    public function withContext(): static;
}
