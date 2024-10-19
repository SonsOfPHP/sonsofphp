<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\File\Bard;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\Worker\File\Bard\AddPackageWorker;
use SonsOfPHP\Bard\Worker\WorkerInterface;

#[Group('bard')]
#[CoversClass(AddPackageWorker::class)]
final class AddPackageWorkerTest extends TestCase
{
    public function testItImplementsCorrectInterface(): void
    {
        $worker = new AddPackageWorker([
            'path'       => 'src/test',
            'repository' => 'git@github.com:vendor/repo.git',
        ]);

        $this->assertInstanceOf(WorkerInterface::class, $worker);
    }
}
