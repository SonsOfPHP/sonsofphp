<?php

declare(strict_types=1);

namespace Chorale\Plan;

/**
 * A single actionable step in the plan.
 */
interface PlanStepInterface
{
    /** e.g., "split", "package-version-update", "composer-root-update" */
    public function type(): string;

    /** A stable identifier used for dedupe or apply; usually the package path. */
    public function id(): string;

    /** Public payload for JSON output & apply. @return array<string,mixed> */
    public function toArray(): array;
}
