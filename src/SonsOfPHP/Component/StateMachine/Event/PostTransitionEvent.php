<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Event;

use BackedEnum;
use SonsOfPHP\Component\EventDispatcher\AbstractStoppableEvent;
use SonsOfPHP\Contract\StateMachine\Event\StateMachineEventInterface;
use SonsOfPHP\Contract\StateMachine\StateMachineInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class PostTransitionEvent extends AbstractStoppableEvent implements StateMachineEventInterface
{
    public function __construct(
        private readonly object $subject,
        private readonly BackedEnum|string $transition,
        private readonly array $context,
        private readonly StateMachineInterface $stateMachine,
    ) {}

    public function getSubject(): object
    {
        return $this->subject;
    }

    public function getTransition(): BackedEnum|string
    {
        return $this->transition;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getStateMachine(): StateMachineInterface
    {
        return $this->stateMachine;
    }
}
