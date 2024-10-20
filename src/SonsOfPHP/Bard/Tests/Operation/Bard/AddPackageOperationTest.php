<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Bard;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Bard\AddPackageOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(AddPackageOperation::class)]
final class AddPackageOperationTest extends TestCase
{
    private AddPackageOperation $worker;

    private JsonFileInterface&MockObject $jsonFile;

    protected function setUp(): void
    {
        $this->worker = new AddPackageOperation([
            'path'       => 'src/test',
            'repository' => 'git@github.com:vendor/repo.git',
        ]);

        $this->jsonFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
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
                $this->callback(fn($packages): true => [[
                    'path'       => 'src/test',
                    'repository' => 'git@github.com:vendor/repo.git',
                ]] === $packages)
            )
        ;

        $this->worker->apply($this->jsonFile);
    }
}
