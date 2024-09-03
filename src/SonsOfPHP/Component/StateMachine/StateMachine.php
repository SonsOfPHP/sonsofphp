<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine;

use BackedEnum;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\StateMachine\Event\GuardEvent;
use SonsOfPHP\Component\StateMachine\Event\PostTransitionEvent;
use SonsOfPHP\Component\StateMachine\Event\PreTransitionEvent;
use SonsOfPHP\Component\StateMachine\Exception\InvalidArgumentException;
use SonsOfPHP\Component\StateMachine\Exception\StateMachineException;
use SonsOfPHP\Component\StateMachine\Exception\UndefinedTransitionException;
use SonsOfPHP\Component\StateMachine\Exception\UnsupportedSubjectException;
use SonsOfPHP\Contract\StateMachine\StateMachineInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class StateMachine implements StateMachineInterface
{
    /**
     * - graph (string)
     * - supports (array|string)
     * - state_getter (string)
     * - state_setter (string)
     * - transitions (array|BackedEnum)
     */
    public function __construct(
        private array $config,
        private readonly ?EventDispatcherInterface $dispatcher = null,
    ) {
        if (empty($config['graph'])) {
            $this->config['graph'] = 'unknown';
        }

        if (empty($config['state_getter'])) {
            $this->config['state_getter'] = 'getState';
        }

        if (empty($config['state_setter'])) {
            $this->config['state_setter'] = 'setState';
        }

        if (empty($config['supports'])) {
            throw new InvalidArgumentException('"supports" is required');
        }
        if (is_string($config['supports'])) {
            $this->config['supports'] = [$config['supports']];
        }

        if (empty($config['transitions'])) {
            throw new InvalidArgumentException('"transitions" is required');
        }
    }

    public function getGraphName(): string
    {
        return $this->config['graph'];
    }

    public function can(object $subject, BackedEnum|string $transition, array $context = []): bool
    {
        if (!$this->supports($subject)) {
            throw new UnsupportedSubjectException('Unsupported Subject');
        }

        if (!array_key_exists($transition, $this->getSupportedTransitions())) {
            throw new UndefinedTransitionException('Undefined Transition');
        }

        $transitionConfig = $this->getTransition($transition);
        $currentState     = $this->getState($subject);

        if (!in_array($currentState, $transitionConfig['from'])) {
            return false;
        }

        if (array_key_exists('callbacks', $transitionConfig) && array_key_exists('guard', $transitionConfig['callbacks'])) {
            foreach ($transitionConfig['callbacks']['guard'] as $name => $callbackConfig) {
                if (array_key_exists('do', $callbackConfig) && false === call_user_func($callbackConfig['do'], $subject, $transition, $context, $this)) {
                    return false;
                }
            }
        }

        if ($this->dispatcher instanceof EventDispatcherInterface && !$this->dispatcher->dispatch(new GuardEvent($subject, $transition, $context, $this))->allows()) {
            return false;
        }

        return true;
    }

    public function apply(object $subject, BackedEnum|string $transition, array $context = []): void
    {
        if (!$this->supports($subject)) {
            throw new UnsupportedSubjectException('Unsupported Subject');
        }

        if (!$this->can($subject, $transition, $context)) {
            throw new StateMachineException('Cannot transition subject to new state');
        }

        $currentState     = $this->getState($subject);
        $newState         = $this->getTransition($transition)['to'];
        $transitionConfig = $this->getTransition($transition);

        if (array_key_exists('callbacks', $transitionConfig) && array_key_exists('pre', $transitionConfig['callbacks'])) {
            foreach ($transitionConfig['callbacks']['pre'] as $name => $callbackConfig) {
                if (array_key_exists('do', $callbackConfig)) {
                    call_user_func($callbackConfig['do'], $subject, $transition, $context, $this);
                }
            }
        }

        if ($this->dispatcher instanceof EventDispatcherInterface) {
            $this->dispatcher->dispatch(new PreTransitionEvent($subject, $transition, $context, $this));
        }

        $this->setState($subject, $newState);

        if (array_key_exists('callbacks', $transitionConfig) && array_key_exists('post', $transitionConfig['callbacks'])) {
            foreach ($transitionConfig['callbacks']['post'] as $name => $callbackConfig) {
                if (array_key_exists('do', $callbackConfig)) {
                    call_user_func($callbackConfig['do'], $subject, $transition, $context, $this);
                }
            }
        }

        if ($this->dispatcher instanceof EventDispatcherInterface) {
            $this->dispatcher->dispatch(new PostTransitionEvent($subject, $transition, $context, $this));
        }
    }

    public function getState(object $subject): BackedEnum|string
    {
        if (!$this->supports($subject)) {
            throw new UnsupportedSubjectException('Unsupported Subject');
        }

        $getter = $this->config['state_getter'];

        return $subject->$getter();
    }

    private function setState(object $subject, BackedEnum|string $state): void
    {
        $setter = $this->config['state_setter'];
        $subject->$setter($state);
    }

    private function supports(object $subject): bool
    {
        foreach ($this->config['supports'] as $class) {
            if (is_a($subject, $class, true)) {
                return true;
            }
        }

        return false;
    }

    private function getSupportedTransitions(): array
    {
        return $this->config['transitions'];
    }

    private function getTransition(BackedEnum|string $transition): array
    {
        return $this->config['transitions'][$transition];
    }
}
