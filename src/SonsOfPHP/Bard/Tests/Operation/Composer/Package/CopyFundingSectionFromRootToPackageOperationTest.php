<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Tests\Operation\Composer\Package;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\Composer\Package\CopyFundingSectionFromRootToPackageOperation;
use SonsOfPHP\Bard\Operation\OperationInterface;

#[Group('bard')]
#[CoversClass(CopyFundingSectionFromRootToPackageOperation::class)]
final class CopyFundingSectionFromRootToPackageOperationTest extends TestCase
{
    private CopyFundingSectionFromRootToPackageOperation $worker;

    private JsonFileInterface&MockObject $rootFile;

    private JsonFileInterface&MockObject $packageFile;

    protected function setUp(): void
    {
        $this->rootFile = $this->createMock(JsonFileInterface::class);
        $this->worker = new CopyFundingSectionFromRootToPackageOperation($this->rootFile);
        $this->packageFile = $this->createMock(JsonFileInterface::class);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(OperationInterface::class, $this->worker);
    }

    public function testItWillUpdateFundingSection(): void
    {
        $funding = [
            [
                'type' => 'Sons Of PHP',
                'url'  => 'https://sonsofphp.com',
            ],
        ];
        $this->rootFile->method('getSection')->willReturn($funding);

        $this->packageFile->expects($this->once())->method('setSection')
            ->with(
                'funding',
                $this->callback(fn($value): true => $value === $funding)
            )
        ;

        $this->worker->apply($this->packageFile);
    }
}
