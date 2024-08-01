<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Logger;

use DateTimeImmutable;

/**
 * "Record" is a log record, this is what is used by Handlers, Formatters, and Enrichers
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface RecordInterface
{
    public function getChannel(): string;

    public function withChannel(string $channel): static;

    public function getLevel();

    public function withLevel(LevelInterface $level): static;

    public function getMessage(): string;

    public function withMessage(string $message): static;

    public function getContext(): ContextInterface;

    public function withContext(ContextInterface $context): static;

    public function getDatetime(): DateTimeImmutable;

    public function withDatetime(DateTimeImmutable $datetime): static;
}
