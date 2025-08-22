<?php

declare(strict_types=1);

namespace Chorale\Plan;

final readonly class SplitStep implements PlanStepInterface
{
    /** @param list<string> $reasons */
    public function __construct(
        private string $path,
        private string $name,
        private string $repo,
        private string $branch,
        private string $splitter,
        private string $tagStrategy,
        private bool $keepHistory,
        private bool $skipIfUnchanged,
        private array $reasons = []
    ) {}

    public function type(): string
    {
        return 'split';
    }

    public function id(): string
    {
        return $this->path;
    }

    public function toArray(): array
    {
        return [
            'type'              => $this->type(),
            'path'              => $this->path,
            'name'              => $this->name,
            'repo'              => $this->repo,
            'branch'            => $this->branch,
            'splitter'          => $this->splitter,
            'tag_strategy'      => $this->tagStrategy,
            'keep_history'      => $this->keepHistory,
            'skip_if_unchanged' => $this->skipIfUnchanged,
            'reasons'           => $this->reasons,
        ];
    }
}
