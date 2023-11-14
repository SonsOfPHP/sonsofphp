<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Handler;

use SonsOfPHP\Contract\Logger\FilterInterface;
use SonsOfPHP\Contract\Logger\FormatterInterface;
use SonsOfPHP\Contract\Logger\HandlerInterface;
use SonsOfPHP\Contract\Logger\RecordInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractHandler implements HandlerInterface
{
    protected FilterInterface $filter;

    public function __construct(
        protected ?FormatterInterface $formatter = null,
    ) {}

    public function getFilter(): ?FilterInterface
    {
        return $this->filter ?? null;
    }

    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    public function getFormatter(): ?FormatterInterface
    {
        return $this->formatter;
    }

    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function handle(RecordInterface $record): void
    {
        if (null !== $this->filter && false === $this->filter->isLoggable($record)) {
            return;
        }

        if (null !== $this->formatter) {
            $record = $record->withMessage($this->formatter->formatMessage($record));
        }

        // ... doHandle
    }
}
