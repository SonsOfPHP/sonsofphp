<?php

declare(strict_types=1);

namespace Chorale\Tests\Run;

use Chorale\Run\StepExecutorInterface;
use Chorale\Run\StepExecutorRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(StepExecutorRegistry::class)]
#[Group('unit')]
#[Small]
final class StepExecutorRegistryTest extends TestCase
{
    #[Test]
    public function testExecuteUsesMatchingExecutor(): void
    {
        $executor = new class implements StepExecutorInterface {
            public bool $called = false;

            public function supports(array $step): bool
            {
                return $step['type'] === 'x';
            }

            public function execute(string $projectRoot, array $step): void
            {
                $this->called = true;
            }
        };
        $registry = new StepExecutorRegistry([$executor]);
        $registry->execute('/tmp', ['type' => 'x']);
        $this->assertTrue($executor->called);
    }

    #[Test]
    public function testExecuteThrowsForUnknownStep(): void
    {
        $registry = new StepExecutorRegistry();
        $this->expectException(RuntimeException::class);
        $registry->execute('/tmp', ['type' => 'missing']);
    }
}
