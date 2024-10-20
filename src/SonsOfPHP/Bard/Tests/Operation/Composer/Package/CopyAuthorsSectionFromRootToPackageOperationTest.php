<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Composer\Package;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyAuthorsSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(CopyAuthorsSectionFromRootToPackageOperation::class)]
final class CopyAuthorsSectionFromRootToPackageOperationTest extends TestCase
{
    private CopyAuthorsSectionFromRootToPackageOperation $worker;

    private JsonFileInterface&MockObject $rootFile;

    private JsonFileInterface&MockObject $packageFile;

    protected function setUp(): void
    {
        $this->rootFile = $this->createMock(JsonFileInterface::class);
        $this->worker = new CopyAuthorsSectionFromRootToPackageOperation($this->rootFile);
        $this->packageFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillUpdateAuthorsSection(): void
    {
        $authors = [
            [
                'name'     => 'Sons Of PHP',
                'homepage' => 'https://sonsofphp.com',
            ],
        ];
        $this->rootFile->method('getSection')->willReturn($authors);

        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'authors',
                $this->callback(fn($value): true => $value === $authors)
            )
        ;

        $this->worker->apply($this->packageFile);
    }
}
