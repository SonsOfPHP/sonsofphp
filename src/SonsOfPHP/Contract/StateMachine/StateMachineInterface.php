<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\StateMachine;

use BackedEnum;
use SonsOfPHP\Contract\StateMachine\Exception\StateMachineExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface StateMachineInterface
{
    /**
     * Returns the name of the graph name of the state machine
     */
    public function getGraphName(): string;

    /**
     * Checks to see if an object can be transitioned
     *
     * @throws StateMachineExceptionInterface
     */
    public function can(object $subject, BackedEnum|string $transition, array $context = []): bool;

    /**
     * Apply a given transition
     *
     * @throws StateMachineExceptionInterface
     */
    public function apply(object $subject, BackedEnum|string $transition, array $context = []): void;

    /**
     * @throws StateMachineExceptionInterface
     */
    public function getState(object $subject): BackedEnum|string;
}
