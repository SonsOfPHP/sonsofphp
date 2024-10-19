<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\File\Bard;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Worker\File\Bard\AddPackageWorker;
use SonsOfPHP\Bard\Worker\WorkerInterface;

#[Group('bard')]
#[CoversClass(AddPackageWorker::class)]
final class AddPackageWorkerTest extends TestCase
{
    private AddPackageWorker $worker;

    private JsonFileInterface&MockObject $jsonFile;

    protected function setUp(): void
    {
        $this->worker = new AddPackageWorker([
            'path'       => 'src/test',
            'repository' => 'git@github.com:vendor/repo.git',
        ]);

        $this->jsonFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(WorkerInterface::class, $this->worker);
    }

    public function testItWillThrowExceptionWhenPackageAtSamePathExists(): void
    {
        $this->jsonFile->method('getSection')->willReturn([
            [
                'path'       => 'src/test',
                'repository' => 'git@github.com:vendor/repo.git',
            ],
        ]);

        $this->expectException('Exception');
        $this->worker->apply($this->jsonFile);
    }

    public function testItWillAddNewPackage(): void
    {
        $this->jsonFile->expects($this->once())->method('setSection')
            ->with(
                'packages',
                $this->callback(function ($packages): true {
                    $this->assertSame([[
                        'path'       => 'src/test',
                        'repository' => 'git@github.com:vendor/repo.git',
                    ]], $packages);
                    return true;
                })
            )
        ;

        $this->worker->apply($this->jsonFile);
    }
}
