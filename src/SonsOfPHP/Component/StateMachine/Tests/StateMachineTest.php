<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\StateMachine\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\StateMachine\StateMachine;
use SonsOfPHP\Contract\StateMachine\StateMachineInterface;

#[CoversClass(StateMachine::class)]
final class StateMachineTest extends TestCase
{
    protected $stateMachine;

    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new class {
            public string $state = 'draft';

            public $data;

            public function getState(): string
            {
                return $this->state;
            }

            public function setState(string $state): void
            {
                $this->state = $state;
            }
        };

        $this->stateMachine = new StateMachine([
            'supports'      => $this->subject::class,
            'initial_state' => 'draft',
            'transitions'   => [
                'create' => [
                    'from' => ['draft'],
                    'to'   => 'new',
                ],
                'fulfill' => [
                    'from' => ['new'],
                    'to'   => 'fulfilled',
                ],
                'cancel' => [
                    'from' => ['draft', 'new', 'fulfilled'],
                    'to'   => 'canceled',
                ],
                'test-guard-callback' => [
                    'from' => ['draft', 'new', 'fulfilled'],
                    'to'   => 'canceled',
                    'callbacks' => [
                        'guard' => [
                            'you-shall-not-pass' => [
                                'do' => fn(): false => false,
                            ],
                        ],
                    ],
                ],
                'test-pre-callback' => [
                    'from' => ['draft', 'new', 'fulfilled'],
                    'to'   => 'canceled',
                    'callbacks' => [
                        'pre' => [
                            'testing' => [
                                'do' => function ($subject): void { $subject->data = 'pre-callback'; },
                            ],
                        ],
                    ],
                ],
                'test-post-callback' => [
                    'from' => ['draft', 'new', 'fulfilled'],
                    'to'   => 'canceled',
                    'callbacks' => [
                        'post' => [
                            'testing' => [
                                'do' => function ($subject): void { $subject->data = 'post-callback'; },
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(StateMachineInterface::class, $this->stateMachine);
    }

    public function testItCanReturnGraphName(): void
    {
        $this->assertSame('unknown', $this->stateMachine->getGraphName());
    }

    public function testItCan(): void
    {
        $this->assertTrue($this->stateMachine->can($this->subject, 'create'));
        $this->assertFalse($this->stateMachine->can($this->subject, 'fulfill'));
        $this->assertTrue($this->stateMachine->can($this->subject, 'cancel'));
    }

    public function testItCanReturnObjectState(): void
    {
        $this->assertSame('draft', $this->stateMachine->getState($this->subject));
    }

    public function testItCanTransitionSubject(): void
    {
        $this->stateMachine->apply($this->subject, 'create');
        $this->assertSame('new', $this->subject->getState());
    }

    public function testItShallNotPass(): void
    {
        $this->assertFalse($this->stateMachine->can($this->subject, 'test-guard-callback'));
    }

    public function testItCanUsePreCallbacks(): void
    {
        $this->stateMachine->apply($this->subject, 'test-pre-callback');
        $this->assertSame('pre-callback', $this->subject->data);
    }

    public function testItCanUsePostCallbacks(): void
    {
        $this->stateMachine->apply($this->subject, 'test-post-callback');
        $this->assertSame('post-callback', $this->subject->data);
    }
}
